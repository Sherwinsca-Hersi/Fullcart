<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Datepicker Example</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
    <input type="text" id="datepicker1">
    <div id="display1"></div>
    <input type="text" id="datepicker2">
    <div id="display2"></div>

    <script>
        const dates = { today: '15/08/2024' };

        function initializeDatePicker(datepickerID, displayID, dateKey) {
            console.log(datepickerID, displayID, dateKey);
            $(datepickerID).datepicker({
                showOn: "button",
                buttonImage: "../assets/images/date_icon.png",
                buttonImageOnly: true,
                buttonText: "Select date",
                dateFormat: "dd/mm/yy",
                defaultDate: dates[dateKey],
                onSelect: function(selectedDate) {
                    updateSelectedDate(displayID, selectedDate, dateKey);
                }
            });
            $(displayID).text(dates[dateKey]);
        }

        function updateSelectedDate(displayID, selectedDate, dateKey) {
            // Implementation for updating the display with the selected date
            $(displayID).text(selectedDate);
        }

        $(document).ready(function() {
            initializeDatePicker('#datepicker1', '#display1', 'today');
            initializeDatePicker('#datepicker2', '#display2', 'today');
        });
    </script>
</body>
</html>