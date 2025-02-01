//date Picker
// $(function() {
//     function getParameterByName(name, url = window.location.href) {
//         name = name.replace(/[\[\]]/g, '\\$&');
//         var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
//             results = regex.exec(url);
//         if (!results) return null;
//         if (!results[2]) return '';
//         return decodeURIComponent(results[2].replace(/\+/g, ' '));
//     }

//     function formatDate(date) {
//         var d = new Date(date),
//             month = '' + (d.getMonth() + 1),
//             day = '' + d.getDate(),
//             year = d.getFullYear();

//         if (month.length < 2) month = '0' + month;
//         if (day.length < 2) day = '0' + day;

//         return [day, month, year].join('/');
//     }

//     var currentDate = formatDate(new Date());

//     var dates = {
//         startDate1: getParameterByName('startDate1') || '01/01/2024',
//         endDate1: getParameterByName('endDate1') || currentDate,
//     };



//     function updateURLWithDates() {
//         var url = new URL(window.location.href);
//         url.searchParams.set('startDate1', dates.startDate1);
//         url.searchParams.set('endDate1', dates.endDate1);   
//         window.history.replaceState({}, '', url);
//         fetchData();
//     }

//     function fetchData() {
//         console.log("Fetching data with dates:", dates);
//         $.ajax({
//             url: '../api/fetch_data.php',
//             type: 'POST',
//             data: {
//                 startDate1: dates.startDate1,
//                 endDate1: dates.endDate1,
//             },
//             success: function(response) {
//                 console.log("Response:", response); 
//             },
//             error: function(xhr, status, error) {
//                 console.error("AJAX Error:", status, error); 
//             }
//         });
//     }

//     function updateSelectedDate(displayID, selectedDate, dateKey) {
//         $(displayID).text(selectedDate);
//         dates[dateKey] = selectedDate;
//         logDates();
//         updateURLWithDates();
//         fetchData();
//         refreshPageWithDates();
//     }


//         function refreshPageWithDates() {
//             var startDate1 = dates.startDate1;
//             var endDate1 = dates.endDate1;
//             window.location.href = '?startDate1=' + encodeURIComponent(startDate1) + '&endDate1=' + encodeURIComponent(endDate1);
//         }
  
//     // console.log(document.getElementById("datepicker1"));
//     // console.log(document.getElementById("datepicker2"));

//     function initializeDatePicker(datepickerID, displayID, dateKey){
//         // console.log(datepickerID,displayID, dateKey);
//         // console.log(typeof $.fn.datepicker);
//         $(datepickerID).datepicker({
//             showOn: "button",
//             buttonImage: "../assets/images/date_icon.png",
//             buttonImageOnly: true,
//             buttonText: "Select date",
//             dateFormat: "dd/mm/yy",
//             defaultDate: dates[dateKey],
//             onSelect: function(selectedDate) {
//                 updateSelectedDate(displayID, selectedDate, dateKey);
//             }
//         });
//         $(displayID).text(dates[dateKey]);
//     }

//     function logDates() {
//         console.log("Start Date 1:", dates.startDate1, "| End Date 1:", dates.endDate1);
       
//     }

//     // initializeDatePicker("#datepicker1", "#start_date1", "startDate1");
//     // initializeDatePicker("#datepicker2", "#end_date1", "endDate1");

  

//     $(document).ready(function() {
//         initializeDatePicker('#datepicker1', '#start_date1', 'startDate1');
//         initializeDatePicker('#datepicker2', '#end_date1', 'endDate1');
//         updateURLWithDates(); 
//     });
//     logDates();

// });



// // date dropdown

// document.addEventListener('DOMContentLoaded', function() {
//     const dateRangeSelects = document.querySelectorAll('.date_dropdown select');

//     dateRangeSelects.forEach(function(select) {
//         const container = select.parentElement.nextElementSibling;
//         const startDateSpans = container.querySelectorAll('.startDate');
//         const endDateSpans = container.querySelectorAll('.endDate');
//         const startDateInputs = container.querySelectorAll('input[type="text"]:first-of-type');
//         const endDateInputs = container.querySelectorAll('input[type="text"]:last-of-type');

//         select.addEventListener('change', function() {
//             const selectedValue = select.value;
//             const currentEndDateStr = endDateSpans[0].textContent;
//             const currentEndDate = parseDate(currentEndDateStr);

//             startDateSpans.forEach(function(startDateSpan, index) {
//                 let newStartDate;

//                 switch (selectedValue) {
//                     case 'Year-Date':
//                         newStartDate = new Date(currentEndDate.getFullYear(), 0, 1);
//                         // newStartDate = new Date(currentEndDate.getFullYear(), currentEndDate.getMonth(), 1);
//                         console.log(newStartDate);
//                         break;
//                     case 'Month-Date':
//                         newStartDate = new Date(currentEndDate.getFullYear(), currentEndDate.getMonth(), 1); 
//                         break;
//                     case 'Week-Date':
//                         newStartDate = new Date(currentEndDate);
//                         const day = newStartDate.getDay();
//                         const diff = newStartDate.getDate() - day; 
//                         newStartDate.setDate(diff);
//                         break;
//                     case 'Date-Date':
//                         newStartDate = new Date(currentEndDate);
//                         newStartDate.setDate(newStartDate.getDate() - 1);
//                         break;
//                     default:
//                         newStartDate = new Date(currentEndDate);
//                         break;
//                 }

//                 const formattedStartDate = formatDate(newStartDate);
//                 console.log(formattedStartDate);
//                 startDateSpan.textContent = formattedStartDate;
//                 startDateInputs[index].value = formattedStartDate;

//                 const formattedEndDate = formatDate(currentEndDate);
//                 console.log(formattedEndDate);
//                 endDateSpans[index].textContent = formattedEndDate;
//                 endDateInputs[index].value = formattedEndDate;
//                 updateURLWithDateSpan();

//                 function getParameterByName(name, url = window.location.href) {
//                     name = name.replace(/[\[\]]/g, '\\$&');
//                     var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
//                         results = regex.exec(url);
//                     if (!results) return null;
//                     if (!results[2]) return '';
//                     return decodeURIComponent(results[2].replace(/\+/g, ' '));
//                 }
    
//                 var currentDate = formatDate(new Date());

               
//                 function fetchData() {
//                     var dates = {
//                         startDate1: getParameterByName('startDate1') || '01/01/2024',
//                         endDate1: getParameterByName('endDate1') || currentDate,
//                     };
//                     console.log("Fetching data with dates:", dates);
//                     $.ajax({
//                         url: '../api/fetch_data.php',
//                         type: 'POST',
//                         data: {
//                             startDate1: formattedStartDate,
//                             endDate1: formattedEndDate,
//                         },
//                         success: function(response) {
//                             console.log("Response:", response); 
//                             refreshPageWithDates()
//                         },
//                         error: function(xhr, status, error) {
//                             console.error("AJAX Error:", status, error); 
//                         }
//                     });
//                 }
//                 function refreshPageWithDates() {
//                     var startDate1 = formattedStartDate;
//                     var endDate1 = formattedEndDate;
//                     window.location.href = '?startDate1=' + encodeURIComponent(startDate1) + '&endDate1=' + encodeURIComponent(endDate1) + '&selectedDateRange=' + selectedValue;
//                 }
//                 function updateURLWithDateSpan(selectedValue){
//                     var url = new URL(window.location.href);
//                     url.searchParams.set('startDate1',formattedStartDate);
//                     url.searchParams.set('endDate1', formattedEndDate);
//                     url.searchParams.set('selectedDateRange', selectedValue);
//                     window.history.replaceState({}, '', url);
//                     fetchData();
//                 }
//             });
//         });
//     });
// });

// function formatDate(date) {
//     const year = date.getFullYear();
//     let month = date.getMonth() + 1;
//     let day = date.getDate();

//     month = month < 10 ? '0' + month : month;
//     day = day < 10 ? '0' + day : day;

//     return `${day}/${month}/${year}`;
// }

// function parseDate(dateStr) {
//     const [day, month, year] = dateStr.split('/');
//     return new Date(`${year}-${month}-${day}`);
// }




//date-new with arrows

// document.addEventListener('DOMContentLoaded', function() {
//     let currentRange = 'today';
//     let currentStartDate = new Date();
//     let currentEndDate = new Date();

//     // Helper function to parse URL parameters
//     function getQueryParam(param) {
//         const urlParams = new URLSearchParams(window.location.search);
//         return urlParams.get(param);
//     }

//     // Format date as dd/mm/yyyy
//     function formatDate(date) {
//         const day = String(date.getDate()).padStart(2, '0');
//         const month = String(date.getMonth() + 1).padStart(2, '0');
//         const year = date.getFullYear();
//         return `${day}/${month}/${year}`;
//     }

//     // Update the displayed start and end dates
//     function updateDates() {
//         const startDateElem = document.getElementById('startDateText');
//         const endDateElem = document.getElementById('endDateText');
//         startDateElem.textContent = formatDate(currentStartDate);
//         endDateElem.textContent = formatDate(currentEndDate);
//     }

//     // Set date range based on the selected range
//     function setDateRange(range) {
//         const today = new Date();
//         switch (range) {
//             case 'today':
//                 currentStartDate = new Date(today);
//                 currentEndDate = new Date(today);
//                 break;
//             case 'yesterday':
//                 currentStartDate = new Date(today);
//                 currentStartDate.setDate(today.getDate() - 1);
//                 currentEndDate = new Date(currentStartDate);
//                 break;
//             case 'this_week':
//                 currentStartDate = new Date(today);
//                 currentStartDate.setDate(today.getDate() - today.getDay());
//                 currentEndDate = new Date(currentStartDate);
//                 currentEndDate.setDate(currentStartDate.getDate() + 6);
//                 break;
//             case '7_days':
//                 currentStartDate = new Date(today);
//                 currentStartDate.setDate(today.getDate() - 7);
//                 currentEndDate = new Date(today);
//                 break;
//             case '30_days':
//                 currentStartDate = new Date(today);
//                 currentStartDate.setDate(today.getDate() - 30);
//                 currentEndDate = new Date(today);
//                 break;
//             case '1_year':
//                 currentStartDate = new Date(today);
//                 currentStartDate.setFullYear(today.getFullYear() - 1);
//                 currentEndDate = new Date(today);
//                 break;
//         }
//         updateDates();
//         updateURLWithDates();
//         refreshPageWithDates();
//     }
//     function shiftDates(step) {
//         const range = currentRange;
//         switch (range) {
//             case 'today':
//             case 'yesterday':
//                 currentStartDate.setDate(currentStartDate.getDate() + step);
//                 currentEndDate.setDate(currentEndDate.getDate() + step);
//                 break;
//             case 'this_week':
//                 currentStartDate.setDate(currentStartDate.getDate() + step * 7);
//                 currentEndDate.setDate(currentEndDate.getDate() + step * 7);
//                 break;
//             case '7_days':
//             case '30_days':
//                 currentStartDate.setDate(currentStartDate.getDate() + step * (range === '7_days' ? 7 : 30));
//                 currentEndDate.setDate(currentEndDate.getDate() + step * (range === '7_days' ? 7 : 30));
//                 break;
//             case '1_year':
//                 currentStartDate.setFullYear(currentStartDate.getFullYear() + step);
//                 currentEndDate.setFullYear(currentEndDate.getFullYear() + step);
//                 break;
//         }
//         updateDates();
//     }
//     // Update the URL with the selected date range and dates
//     function updateURLWithDates() {
//         const formattedStartDate = formatDate(currentStartDate);
//         const formattedEndDate = formatDate(currentEndDate);
//         const newURL = `${window.location.pathname}?startDate=${formattedStartDate}&endDate=${formattedEndDate}&selectedDateRange=${currentRange}`;
//         history.pushState(null, '', newURL);
//     }

//     // Load initial date range from URL if present
//     function initializeFromURL() {
//         const startDateParam = getQueryParam('startDate');
//         const endDateParam = getQueryParam('endDate');
//         const selectedRangeParam = getQueryParam('selectedDateRange');

//         if (startDateParam && endDateParam && selectedRangeParam) {
//             // Parse the dates and set the date range
//             currentStartDate = new Date(startDateParam.split('/').reverse().join('-'));
//             currentEndDate = new Date(endDateParam.split('/').reverse().join('-'));
//             currentRange = selectedRangeParam;
//         } else {
//             // If no parameters in the URL, default to today
//             setDateRange('today');
//         }

//         updateDates();
//         highlightSelectedButton(currentRange);
//         fetchData();
//     }

//     // Highlight the selected date range button
//     function highlightSelectedButton(selectedRange) {
//         document.querySelectorAll('.date-range-btn').forEach(button => {
//             button.classList.remove('active');
//         });
//         const activeButton = document.querySelector(`[data-range="${selectedRange}"]`);
//         if (activeButton) {
//             activeButton.classList.add('active');
//         }
//     }

//     // Fetch data and update the page
//     function fetchData() {
//         const formattedStartDate = formatDate(currentStartDate);
//         const formattedEndDate = formatDate(currentEndDate);
//         console.log(formattedStartDate,formattedEndDate);
//         // Make the AJAX request to fetch data
//         $.ajax({
//             url: '../api/fetch_data.php',
//             type: 'POST',
//             data: {
//                 startDate: formattedStartDate,
//                 endDate: formattedEndDate,
//             },
//             success: function(response) {
//                 console.log("Response:", response);
//                 // Update the URL with the new date range
//                 updateURLWithDates();
//             },
//             error: function(xhr, status, error) {
//                 console.error("AJAX Error:", status, error);
//             }
//         });
//     }

//     function refreshPageWithDates() {
//         const formattedStartDate = formatDate(currentStartDate);
//         const formattedEndDate = formatDate(currentEndDate);
//         const newURL = `${window.location.pathname}?startDate=${encodeURIComponent(formattedStartDate)}&endDate=${encodeURIComponent(formattedEndDate)}&selectedDateRange=${encodeURIComponent(currentRange)}`;
//         window.location.href = newURL; // Refresh the page with updated URL
//     }
//     // Initialize the date range and UI from the URL parameters if they exist
//     initializeFromURL();

//     // Button click events for date ranges
//     document.querySelectorAll('.date-range-btn').forEach(btn => {
//         btn.addEventListener('click', function() {
//             const selectedRange = this.getAttribute('data-range');
//             if (selectedRange !== currentRange) {
//                 currentRange = selectedRange;
//                 setDateRange(currentRange);
//                 fetchData();
//                 highlightSelectedButton(currentRange);
//             }
//         });
//     });

//     document.querySelectorAll('.date-range-btn').forEach(btn => {
//         btn.addEventListener('click', function() {
//             currentRange = this.getAttribute('data-range');
//             setDateRange(currentRange);
//         });
//     });

//     document.querySelector('.arr-left').addEventListener('click', function() {
//         shiftDates(-1);
//     });

//     document.querySelector('.arr-right').addEventListener('click', function() {
//         shiftDates(1);
//     });

// });


document.addEventListener('DOMContentLoaded', function() {
    let currentRange = 'today';
    let currentStartDate = new Date();
    let currentEndDate = new Date();

    // Helper function to parse URL parameters
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    // Format date as dd/mm/yyyy
    function formatDate(date) {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    // Update the displayed start and end dates
    function updateDates() {
        const startDateElem = document.getElementById('startDateText');
        const endDateElem = document.getElementById('endDateText');
        startDateElem.textContent = formatDate(currentStartDate);
        endDateElem.textContent = formatDate(currentEndDate);
    }

    // Set date range based on the selected range
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
                currentEndDate = new Date(currentStartDate);
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
        refreshPageWithDates();
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
        refreshPageWithDates();
    }

    // Update the URL with the selected date range and dates
    function updateURLWithDates() {
        const formattedStartDate = formatDate(currentStartDate);
        const formattedEndDate = formatDate(currentEndDate);
        const newURL = `${window.location.pathname}?startDate=${formattedStartDate}&endDate=${formattedEndDate}&selectedDateRange=${currentRange}`;
        history.pushState(null, '', newURL);
    }

    // Load initial date range from URL if present
    function initializeFromURL() {
        const startDateParam = getQueryParam('startDate');
        const endDateParam = getQueryParam('endDate');
        const selectedRangeParam = getQueryParam('selectedDateRange');

        if (startDateParam && endDateParam && selectedRangeParam) {
            // Parse the dates and set the date range
            currentStartDate = new Date(startDateParam.split('/').reverse().join('-'));
            currentEndDate = new Date(endDateParam.split('/').reverse().join('-'));
            currentRange = selectedRangeParam;
        } else {
            // If no parameters in the URL, default to today
            setDateRange('today');
        }

        updateDates();
        highlightSelectedButton(currentRange);
        fetchData();
    }

    // Highlight the selected date range button
    function highlightSelectedButton(selectedRange) {
        document.querySelectorAll('.date-range-btn').forEach(button => {
            button.classList.remove('active');
        });
        const activeButton = document.querySelector(`[data-range="${selectedRange}"]`);
        if (activeButton) {
            activeButton.classList.add('active');
        }
    }

    // Fetch data and update the page
    function fetchData() {
        const formattedStartDate = formatDate(currentStartDate);
        const formattedEndDate = formatDate(currentEndDate);
        console.log(formattedStartDate, formattedEndDate);
        // Make the AJAX request to fetch data
        $.ajax({
            url: '../api/fetch_data.php',
            type: 'POST',
            data: {
                startDate: formattedStartDate,
                endDate: formattedEndDate,
            },
            success: function(response) {
                console.log("Response:", response);
                // Handle the response data and update the page
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    }

    function refreshPageWithDates() {
        const formattedStartDate = formatDate(currentStartDate);
        const formattedEndDate = formatDate(currentEndDate);
        const newURL = `${window.location.pathname}?startDate=${encodeURIComponent(formattedStartDate)}&endDate=${encodeURIComponent(formattedEndDate)}&selectedDateRange=${encodeURIComponent(currentRange)}`;
        window.location.href = newURL; // Refresh the page with updated URL
    }
    
    // Initialize the date range and UI from the URL parameters if they exist
    initializeFromURL();

    // Button click events for date ranges
    document.querySelectorAll('.date-range-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const selectedRange = this.getAttribute('data-range');
            if (selectedRange !== currentRange) {
                currentRange = selectedRange;
                setDateRange(currentRange);
                highlightSelectedButton(currentRange);
            }
        });
    });

    // Left and right arrow click events
    document.querySelector('.arr-left').addEventListener('click', function() {
        shiftDates(-1);  // Move the dates backward
    });

    document.querySelector('.arr-right').addEventListener('click', function() {
        shiftDates(1);   // Move the dates forward
    });
});