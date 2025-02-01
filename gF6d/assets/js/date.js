document.addEventListener('DOMContentLoaded', function () {
    let currentRange = 'today';
    let currentStartDate = new Date();
    let currentEndDate = new Date();

    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    function formatDate(date) {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    function updateDates() {
        const startDateElem = document.getElementById('startDateText');
        const endDateElem = document.getElementById('endDateText');
        startDateElem.textContent = formatDate(currentStartDate);
        endDateElem.textContent = formatDate(currentEndDate);
    }

    function setDateRange(range) {
        const today = new Date();
        switch (range) {
            case 'today':
                currentStartDate = new Date(today);
                currentEndDate = new Date(today);
                break;
            case 'yesterday':
                currentStartDate = new Date(today);
                currentStartDate.setDate(today.getDate() - 1);
                currentEndDate = new Date(today);
                break;
            case 'this_week':
                currentStartDate = new Date(today);
                currentStartDate.setDate(today.getDate() - today.getDay());
                currentEndDate = new Date(currentStartDate);
                currentEndDate.setDate(currentStartDate.getDate() + 6);
                break;
            case '7_days':
                currentStartDate = new Date(today);
                currentStartDate.setDate(today.getDate() - 7);
                currentEndDate = new Date(today);
                break;
            case '30_days':
                currentStartDate = new Date(today);
                currentStartDate.setDate(today.getDate() - 30);
                currentEndDate = new Date(today);
                break;
            case '1_year':
                currentStartDate = new Date(today);
                currentStartDate.setFullYear(today.getFullYear() - 1);
                currentEndDate = new Date(today);
                break;
        }
        updateDates();
        updateURLWithDates();
        fetchData();
    }

    function shiftDates(step) {
        const range = currentRange;
        switch (range) {
            case 'today':
            case 'yesterday':
                currentStartDate.setDate(currentStartDate.getDate() + step);
                currentEndDate.setDate(currentEndDate.getDate() + step);
                break;
            case 'this_week':
                currentStartDate.setDate(currentStartDate.getDate() + step * 7);
                currentEndDate.setDate(currentEndDate.getDate() + step * 7);
                break;
            case '7_days':
            case '30_days':
                currentStartDate.setDate(currentStartDate.getDate() + step * (range === '7_days' ? 7 : 30));
                currentEndDate.setDate(currentEndDate.getDate() + step * (range === '7_days' ? 7 : 30));
                break;
            case '1_year':
                currentStartDate.setFullYear(currentStartDate.getFullYear() + step);
                currentEndDate.setFullYear(currentEndDate.getFullYear() + step);
                break;
        }
        updateDates();
        updateURLWithDates();
        fetchData();
    }

    function updateURLWithDates() {
        const formattedStartDate = formatDate(currentStartDate);
        const formattedEndDate = formatDate(currentEndDate);
        const cos_id = localStorage.getItem('cos_id');
        const newURL = `${window.location.pathname}?cos_id=${cos_id}&startDate=${formattedStartDate}&endDate=${formattedEndDate}&selectedDateRange=${currentRange}`;
        history.pushState(null, '', newURL);
    }

    function initializeFromURL() {
        const startDateParam = getQueryParam('startDate');
        const endDateParam = getQueryParam('endDate');
        const selectedRangeParam = getQueryParam('selectedDateRange');

        if (startDateParam && endDateParam && selectedRangeParam) {
            currentStartDate = new Date(startDateParam.split('/').reverse().join('-'));
            currentEndDate = new Date(endDateParam.split('/').reverse().join('-'));
            currentRange = selectedRangeParam;
        } else {
            setDateRange('today');
        }

        updateDates();
        highlightSelectedButton(currentRange);
        fetchData();
    }

    function highlightSelectedButton(selectedRange) {
        document.querySelectorAll('.date-range-btn').forEach(button => {
            button.classList.remove('active');
        });
        const activeButton = document.querySelector(`[data-range="${selectedRange}"]`);
        if (activeButton) {
            activeButton.classList.add('active');
        }
    }


    function fetchData() {
        const formattedStartDate = formatDate(currentStartDate);
        const formattedEndDate = formatDate(currentEndDate);
        console.log(formattedStartDate, formattedEndDate);

        $.ajax({
            url: 'dataFromDates.php',
            type: 'POST',
            data: {
                startDate: formattedStartDate,
                endDate: formattedEndDate,
            },
            success: function(response) {
                console.log("Response:", response);
                updateTable(response);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
            },
        });
    }

    function updateTable(data) {
        console.log(data);

        function getBillType(billType) {
            billType = Number(billType); 
            
            if (billType === 1) {
                return "Online";
            } else if (billType === 2) {
                return "Instore";
            } else {
                return "N/A";
            }
        }
    
        // const tableBody = document.querySelector('#example tbody');
        // tableBody.innerHTML = '';

        // if (Array.isArray(data) && data.length > 0) {
        //     console.log(data);
        //     let rowCount = 1;
        //     data.forEach(item => {
        //         const row = document.createElement('tr');
        //         row.className = rowCount % 2 === 0 ? 'teven' : 'todd';
        //         row.innerHTML = `
        //             <td>${item.id}</td>
        //             <td>${item.invoice_no}</td>
        //             <td data-sort='${item.created_ts}'>${formatDateTime(item.created_ts)}</td>
        //             <td>${getBillType(item.bill_type)}</td>
        //             <td>${item.name}</td>
        //             <td>${item.mobile}</td>
        //             <td>${formatCurrency(item.o_total)}</td>
        //         `;
        //         tableBody.appendChild(row);
        //         rowCount++;
        //     });
        // } else {
        //     tableBody.innerHTML = '<tr><td colspan="7" style="text-align: center;font-weight:bold;">No data found</td></tr>';
        // }
        
                const table = $('#example').DataTable();

                table.clear();

                if (Array.isArray(data) && data.length > 0) {
                data.forEach(item => {
                        table.row.add([
                            item.id,
                            item.invoice_no,
                            formatDateTime(item.created_ts),
                            getBillType(item.bill_type),
                            item.name,
                            item.mobile,
                            formatCurrency(item.o_total)
                        ]);
                });
                table.draw();
                } 
                else {

                    table.clear();
                    table.row.add(['', '', '', 'No data found', '', '', '']).draw();
                }
    }

    function formatDateTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('en-GB', { 
            day: '2-digit', month: '2-digit', year: 'numeric', 
            hour: '2-digit', minute: '2-digit', hour12: true 
        });
    }

    function formatCurrency(amount) {
        return Number(amount).toFixed(2);
    }


    initializeFromURL();

    document.querySelectorAll('.date-range-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const selectedRange = this.getAttribute('data-range');
            if (selectedRange !== currentRange) {
                currentRange = selectedRange;
                setDateRange(currentRange);
                highlightSelectedButton(currentRange);
            }
        });
    });

    document.querySelector('.arr-left').addEventListener('click', function () {
        shiftDates(-1);
    });

    document.querySelector('.arr-right').addEventListener('click', function () {
        shiftDates(1);
    });
});
