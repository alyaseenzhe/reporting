
// Alpine component for table totals
function tableTotals() {
    return {
        totals: {},

        calculate() {
            this.totals = {};
            const rows = this.$el.querySelectorAll('tbody tr');

            rows.forEach(row => {
                // calculate column totals
                for (const key in row.dataset) {
                    this.totals[key] = (this.totals[key] || 0) + Number(row.dataset[key] || 0);
                }

                // calculate row total dynamically
                const rowTotalCell = row.querySelector('.row-total');
                if (rowTotalCell) {
                    rowTotalCell.innerText = this.calculateRowTotal(row.dataset);
                }
            });
        },

        calculateRowTotal(rowData) {
            let sum = 0;
            for (const key in rowData) {
                sum += Number(rowData[key] || 0);
            }
            return sum;
        }
    }
}

// recalc after Livewire updates (filters, pagination, etc.)
document.addEventListener('livewire:load', () => {
    Livewire.hook('message.processed', () => {
        const table = document.getElementById('tbl2');
        if (table && table.__x) {
            table.__x.$data.calculate();
        }
    });
});
