<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تجربة Offline</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            font-family: Arial;
            padding: 30px;
            background: #f8fafc;
        }

        .box {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 10px 25px #00000015;
        }

        input, textarea, button {
            width: 100%;
            margin-top: 10px;
            padding: 12px;
            font-size: 16px;
        }

        button {
            background: #f59e0b;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 8px;
        }

        .status {
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>فورم تجربة Offline</h2>

    <input type="text" id="name" placeholder="الاسم">
    <textarea id="note" placeholder="ملاحظة"></textarea>

    <button onclick="saveOffline()">حفظ</button>

    <div class="status" id="status"></div>

    <button onclick="getLocation()">Get My Location</button>
    <p id="output"></p>

    <script>

    </script>

</div>

<script>
    let db = null;

    function setStatus(message) {
        document.getElementById('status').innerText = message;
        console.log(message);
    }

    const dbRequest = indexedDB.open('offline_app_db', 1);

    dbRequest.onupgradeneeded = function (event) {
        db = event.target.result;

        if (!db.objectStoreNames.contains('experiments')) {
            db.createObjectStore('experiments', {
                keyPath: 'offline_uuid'
            });
        }
    };

    dbRequest.onsuccess = function (event) {
        db = event.target.result;
        setStatus(navigator.onLine ? 'متصل بالإنترنت' : 'لا يوجد إنترنت');

        if (navigator.onLine) {
            syncRecords();
        }
    };

    dbRequest.onerror = function () {
        setStatus('خطأ في فتح التخزين المحلي');
    };

    function uuid() {
        return Date.now().toString() + '-' + Math.random().toString(36).substring(2);
    }

    function saveOffline() {
        if (!db) {
            setStatus('التخزين المحلي لم يجهز بعد، أعيدي المحاولة');
            return;
        }

        const name = document.getElementById('name').value;
        const note = document.getElementById('note').value;

        if (!name) {
            alert('اكتبي الاسم');
            return;
        }

        const record = {
            offline_uuid: uuid(),
            name: name,
            note: note,
            synced: false,
            created_at: new Date().toISOString()
        };

        const transaction = db.transaction(['experiments'], 'readwrite');
        const store = transaction.objectStore('experiments');

        store.put(record);

        transaction.oncomplete = function () {
            setStatus('تم الحفظ محليًا');

            document.getElementById('name').value = '';
            document.getElementById('note').value = '';

            if (navigator.onLine) {
                syncRecords();
            }
        };

        transaction.onerror = function () {
            setStatus('فشل الحفظ المحلي');
        };
    }

    function syncRecords() {
        if (!db) {
            setStatus('التخزين المحلي غير جاهز');
            return;
        }

        const transaction = db.transaction(['experiments'], 'readonly');
        const store = transaction.objectStore('experiments');
        const getAllRequest = store.getAll();

        getAllRequest.onsuccess = function () {
            const records = getAllRequest.result.filter(item => item.synced === false);

            if (records.length === 0) {
                setStatus('لا توجد بيانات معلقة للرفع');
                return;
            }

            setStatus('جاري رفع ' + records.length + ' سجل للسيرفر...');

            fetch('/api/offline/experiments/sync', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    records: records
                })
            })
                .then(async response => {
                    const text = await response.text();

                    if (!response.ok) {
                        throw new Error('HTTP ' + response.status + ' - ' + text);
                    }

                    return JSON.parse(text);
                })
                .then(data => {
                    markAsSynced(data.saved);
                    setStatus('تم رفع البيانات للسيرفر بنجاح');
                })
                .catch(error => {
                    console.error(error);
                    setStatus('فشل الرفع: ' + error.message);
                });
        };
    }

    function markAsSynced(savedIds) {
        const transaction = db.transaction(['experiments'], 'readwrite');
        const store = transaction.objectStore('experiments');

        savedIds.forEach(id => {
            const request = store.get(id);

            request.onsuccess = function () {
                const record = request.result;

                if (record) {
                    record.synced = true;
                    store.put(record);
                }
            };
        });
    }

    window.addEventListener('online', function () {
        setStatus('رجع الإنترنت، جاري المزامنة...');
        syncRecords();
    });

    window.addEventListener('offline', function () {
        setStatus('لا يوجد إنترنت، سيتم الحفظ محليًا');
    });

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            document.getElementById("output").innerText = "Geolocation not supported";
        }
    }

    function showPosition(position) {
        document.getElementById("output").innerText =
            "Latitude: " + position.coords.latitude +
            "\nLongitude: " + position.coords.longitude;
    }
</script>

</body>
</html>
