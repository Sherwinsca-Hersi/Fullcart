$(function () {
    function getParameterByName(name, url = window.location.href) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return `${day}/${month}/${year}`;
    }

    function parseDate(dateStr) {
        const [day, month, year] = dateStr.split('/');
        return new Date(`${year}-${month}-${day}`);
    }

    var currentDate = formatDate(new Date());

    let today = new Date();
    let firstDayOfWeek = new Date(today);
    let currentDayOfWeek = today.getDay();
    let daysToSubtract = currentDayOfWeek;
    firstDayOfWeek.setDate(today.getDate() - daysToSubtract);

    var dates = {
        startDate1: getParameterByName('startDate1') || formatDate(firstDayOfWeek),
        endDate1: getParameterByName('endDate1') || currentDate,
    };

    function updateURLWithDates() {
        var url = new URL(window.location.href);
        url.searchParams.set('startDate1', dates.startDate1);
        url.searchParams.set('endDate1', dates.endDate1);
        window.history.replaceState({}, '', url);
        fetchData();
    }

    function fetchData() {
        console.log("Fetching data with dates:", dates);
        $.ajax({
            url: '../api/fetch_data.php',
            type: 'POST',
            data: {
                startDate1: dates.startDate1,
                endDate1: dates.endDate1,
            },
            success: function (response) {
                console.log("Response:", response);
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    }

    function logDates() {
        console.log("Start Date 1:", dates.startDate1, "| End Date 1:", dates.endDate1);
    }

    $(document).ready(function () {
        updateURLWithDates();
    });

    logDates();

    document.addEventListener('DOMContentLoaded', function () {
        const dateButtons = document.querySelectorAll('#dateRangeForm button');
        const startDateSpan = document.getElementById('startDate');
        const endDateSpan = document.getElementById('endDate');

        dateButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const selectedValue = this.getAttribute('data-value');

                const currentEndDateStr = endDateSpan.textContent;
                const currentEndDate = parseDate(currentEndDateStr);

                let newStartDate;
                switch (selectedValue) {
                    case 'today':
                        newStartDate = new Date(currentEndDate);
                        break;
                    case 'yesterday':
                        newStartDate = new Date(currentEndDate);
                        newStartDate.setDate(currentEndDate.getDate() - 1);
                        break;
                    case 'this_week':
                        newStartDate = new Date(currentEndDate);
                        newStartDate.setDate(currentEndDate.getDate() - currentEndDate.getDay());
                        break;
                    case '7_days':
                        newStartDate = new Date(currentEndDate);
                        newStartDate.setDate(currentEndDate.getDate() - 7);
                        break;
                    case '30_days':
                        newStartDate = new Date(currentEndDate);
                        newStartDate.setDate(currentEndDate.getDate() - 30);
                        break;
                    case '1_year':
                        newStartDate = new Date(currentEndDate);
                        newStartDate.setFullYear(currentEndDate.getFullYear() - 1);
                        break;
                    default:
                        newStartDate = new Date(currentEndDate);
                        break;
                }

                const formattedStartDate = formatDate(newStartDate);
                startDateSpan.textContent = formattedStartDate;

                const formattedEndDate = formatDate(currentEndDate);
                endDateSpan.textContent = formattedEndDate;

                updateURLWithDateSpan(formattedStartDate, formattedEndDate, selectedValue);
            });
        });

        function updateURLWithDateSpan(startDate, endDate, selectedValue) {
            var url = new URL(window.location.href);
            url.searchParams.set('startDate1', startDate);
            url.searchParams.set('endDate1', endDate);
            url.searchParams.set('selectedDateRange', selectedValue);
            window.history.replaceState({}, '', url);
            dates.startDate1 = startDate;
            dates.endDate1 = endDate;
            fetchData();
        }
    });
});