//date Picker
$(function() {
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

        return [day, month, year].join('/');
    }

    var currentDate = formatDate(new Date());
    

let today = new Date();
let firstDayOfWeek = new Date(today);
let currentDayOfWeek = today.getDay();
let daysToSubtract = currentDayOfWeek;
firstDayOfWeek.setDate(today.getDate() - daysToSubtract);
function formatDate(date) {
    let day = date.getDate().toString().padStart(2, '0'); 
    let month = (date.getMonth() + 1).toString().padStart(2, '0');
    let year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

console.log(formatDate(firstDayOfWeek));
    var dates = {
        startDate1: getParameterByName('startDate1') || formatDate(today),
        endDate1: getParameterByName('endDate1') || currentDate,
    };
    console.log(dates)



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
            success: function(response) {
                console.log("Response:", response); 
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error); 
            }
        });
    }

    // function updateSelectedDate() {
    //     // $(displayID).text(selectedDate);
    //     // dates[dateKey] = selectedDate;
    //     logDates();
    //     updateURLWithDates();
    //     fetchData();
    //     refreshPageWithDates();
    // }


        // function refreshPageWithDates() {
        //     var startDate1 = dates.startDate1;
        //     var endDate1 = dates.endDate1;
        //     window.location.href = '?startDate1=' + encodeURIComponent(startDate1) + '&endDate1=' + encodeURIComponent(endDate1);
        // }
       
  
    // console.log(document.getElementById("datepicker1"));
    // console.log(document.getElementById("datepicker2"));

    // function initializeDatePicker(datepickerID, displayID, dateKey){
    //     // console.log(datepickerID,displayID, dateKey);
    //     // console.log(typeof $.fn.datepicker);
    //     $(datepickerID).datepicker({
    //         showOn: "button",
    //         buttonImage: "../assets/images/date_icon.png",
    //         buttonImageOnly: true,
    //         buttonText: "Select date",
    //         dateFormat: "dd/mm/yy",
    //         defaultDate: dates[dateKey],
    //         onSelect: function(selectedDate) {
    //             updateSelectedDate(displayID, selectedDate, dateKey);
    //         }
    //     });
    //     $(displayID).text(dates[dateKey]);
    // }

    function logDates() {
        console.log("Start Date 1:", dates.startDate1, "| End Date 1:", dates.endDate1);
       
    }

    // initializeDatePicker("#datepicker1", "#start_date1", "startDate1");
    // initializeDatePicker("#datepicker2", "#end_date1", "endDate1");

  

    $(document).ready(function() {
        // initializeDatePicker('#datepicker1', '#start_date1', 'startDate1');
        // initializeDatePicker('#datepicker2', '#end_date1', 'endDate1');
        updateURLWithDates(); 
    });
    logDates();

});



// date dropdown

document.addEventListener('DOMContentLoaded', function() {
    const dateRadioBtns = document.querySelectorAll('#dateRangeForm label input');
    dateRadioBtns.forEach(function(select) {
        // const container = select.parentElement.nextElementSibling;
        // console.log(container);
        const startDateSpan = document.getElementById('startDate');
        const endDateSpan = document.getElementById('endDate');
        // const startDateInputs = container.querySelectorAll('input[type="text"]:first-of-type');
        // const endDateInputs = container.querySelectorAll('input[type="text"]:last-of-type');

        select.addEventListener('change', function() {
            const selectedValue = select.value;
            
            const currentEndDateStr = endDateSpan.textContent;
            const currentEndDate = parseDate(currentEndDateStr);

            // startDateSpan.forEach(function(startDateSpan) {
                let newStartDate;
                console.log(currentEndDate);
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
                        console.log(currentEndDate);
                        newStartDate = new Date(currentEndDate); // Copy current date
                        newStartDate.setFullYear(currentEndDate.getFullYear() - 1); // Modify the copy
                        break;
                    default:
                        newStartDate = new Date(currentEndDate);
                        break;
                }
                console.log(currentEndDate);
                // updateSelectedDate();
                const formattedStartDate = formatDate(newStartDate);
                console.log(startDateSpan);
                // console.log(formattedStartDate);
                startDateSpan.textContent = formattedStartDate;
                // startDateInputs[index].value = formattedStartDate;
                console.log(currentEndDate);
                const formattedEndDate = formatDate(currentEndDate);
                console.log(formattedEndDate);
                endDateSpan.textContent = formattedEndDate;
                // endDateInputs[index].value = formattedEndDate;
                updateURLWithDateSpan();

                function getParameterByName(name, url = window.location.href) {
                    name = name.replace(/[\[\]]/g, '\\$&');
                    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                        results = regex.exec(url);
                    if (!results) return null;
                    if (!results[2]) return '';
                    return decodeURIComponent(results[2].replace(/\+/g, ' '));
                }
    
                var currentDate = formatDate(new Date());

               
                function fetchData() {
                    var dates = {
                        startDate1: getParameterByName('startDate1') || formatDate(firstDayOfWeek),
                        endDate1: getParameterByName('endDate1') || currentDate,
                    };
                    console.log("Fetching data with dates:", dates);
                    $.ajax({
                        url: '../api/fetch_data.php',
                        type: 'POST',
                        data: {
                            startDate1: formattedStartDate,
                            endDate1: formattedEndDate,
                        },
                        success: function(response) {
                            console.log("Response:", response); 
                            refreshPageWithDates()
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", status, error); 
                        }
                    });
                }
                function refreshPageWithDates() {
                    var startDate1 = formattedStartDate;
                    var endDate1 = formattedEndDate;
                    console.log(endDate1);
                    window.location.href = '?startDate1=' + encodeURIComponent(startDate1) + '&endDate1=' + encodeURIComponent(endDate1) + '&selectedDateRange=' + selectedValue;
                }
                function updateURLWithDateSpan(selectedValue){
                    var url = new URL(window.location.href);
                    url.searchParams.set('startDate1',formattedStartDate);
                    url.searchParams.set('endDate1', formattedEndDate);
                    url.searchParams.set('selectedDateRange', selectedValue);
                    //window.history.replaceState({}, '', url);
                    fetchData();
                }
            // });
        });
    });
});

function formatDate(date) {
    const year = date.getFullYear();
    let month = date.getMonth() + 1;
    let day = date.getDate();

    month = month < 10 ? '0' + month : month;
    day = day < 10 ? '0' + day : day;

    return `${day}/${month}/${year}`;
}

function parseDate(dateStr) {
    const [day, month, year] = dateStr.split('/');
    return new Date(`${year}-${month}-${day}`);
}

