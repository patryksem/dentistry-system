$(function() {


        let monthFormSelect = $('#visit_form_visitDate_date_month');
        let dayFormSelect = $('#visit_form_visitDate_date_day');
        let yearFormSelect = $('#visit_form_visitDate_date_year');
        let hourFormSelect = $('#visit_form_visitDate_time_hour');
        let minuteFormSelect = $('#visit_form_visitDate_time_minute');


        if (monthFormSelect.length > 0) {
            $.ajax({
                url: '/department/timetable',
                type: 'GET',
                success: function (settings) {
                    settings.autoClose = true;
                    settings.language = "pl";

                    $('#bookVisitDate').removeAttr("disabled");
                    let datapicker = $('.datepicker').datepicker(settings);

                    datapicker.on('changeDate', function (e) {

                        let selectedDate = e.date;

                        monthFormSelect.val(selectedDate.getMonth() + 1);
                        dayFormSelect.val(selectedDate.getDate());
                        yearFormSelect.val(selectedDate.getFullYear());


                        $.ajax({
                            url: window.location.href + "/" + (selectedDate.getTime() / 1000),
                            type: 'GET',
                            success: function (availableHours) {

                                let selectHours = $('#bookVisitHour');
                                selectHours.removeAttr("disabled");
                                $.each(availableHours, function (index, value) {
                                    selectHours.append(new Option(value, value))
                                });
                            }
                        });

                    });
                }
            });
        }

    $('#bookVisitHour').change(function () {

        let selectedTime = $(this).val();

        if(selectedTime.length == 5) {
            $('#btn-book-visit').removeAttr("disabled");
        } else {
            $('#btn-book-visit').attr("disabled", "disabled");
            return false;
        }

         hourFormSelect.val(parseInt(selectedTime.substring(0,2)))
         minuteFormSelect.val(parseInt(selectedTime.substring(3,5)))
    });

});
