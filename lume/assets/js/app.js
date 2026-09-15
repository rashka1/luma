// my jquery code


// search members

var search_timer;

// wait after typing
$('#member-search').on('keyup', function () {
    clearTimeout(search_timer);
    search_timer = setTimeout(search_members, 300);
});

function search_members() {
    var search = $('#member-search').val();

    $.post('../ajax/search_member.php', { search: search }, function (response) {
        if (response.success == false) {
            alert(response.message);
            return;
        }

        var rows = $('#member-rows');
        rows.empty();

        if (response.data.length == 0) {
            rows.append('<tr><td colspan="6">No members match that search.</td></tr>');
            return;
        }

        $.each(response.data, function (index, member) {
            var row = $('<tr></tr>');
            row.append($('<td></td>').text(member.full_name));
            row.append($('<td></td>').text(member.phone));
            row.append($('<td></td>').text(member.email || ''));
            var status = $('<span></span>').addClass('status-badge status-' + member.status).text(member.status);
            row.append($('<td></td>').append(status));
            row.append($('<td></td>').text(member.join_date));

            var buttons = $('<td></td>');
            buttons.append('<a class="btn btn-sm btn-outline-lume" href="member_form.php?id=' + member.id + '">Edit</a> ');

            // delete button only for admin
            if (rows.data('admin') == 1) {
                buttons.append(
                    '<form method="post" action="member_delete.php" class="d-inline" onsubmit="return confirm(\'Delete this member?\');">' +
                    '<input type="hidden" name="id" value="' + member.id + '">' +
                    '<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>' +
                    '</form>'
                );
            }
            row.append(buttons);

            rows.append(row);
        });
    }, 'json').fail(function () {
        alert('Could not reach the server. Please try again.');
    });
}


// places left (new booking)

$('#class_id, #member_id').on('change', check_places);

// class already selected
if ($('#class_id').length > 0 && $('#class_id').val() != '') {
    check_places();
}

function check_places() {
    var class_id = $('#class_id').val();
    var member_id = $('#member_id').val();

    if (class_id == '') {
        $('#places-left').text('');
        $('#save-booking').prop('disabled', false);
        return;
    }

    $.post('../ajax/check_capacity.php', { class_id: class_id, member_id: member_id }, function (response) {
        $('#places-left').text(response.message);

        if (response.success && response.data.can_book) {
            $('#places-left').attr('class', 'text-success');
            $('#save-booking').prop('disabled', false);
        } else {
            // the server checks again when saving
            $('#places-left').attr('class', 'text-danger');
            $('#save-booking').prop('disabled', true);
        }
    }, 'json').fail(function () {
        alert('Could not reach the server. Please try again.');
    });
}


// present / absent

$('.mark-button').on('click', function () {
    var button = $(this);
    var booking_id = button.data('booking');
    var status = button.data('status');

    $.post('../ajax/mark_attendance.php', { booking_id: booking_id, status: status }, function (response) {
        if (response.success == false) {
            alert(response.message);
            return;
        }

        // reset the buttons
        var row = button.closest('tr');
        row.find('.present-button').removeClass('btn-success').addClass('btn-outline-success');
        row.find('.absent-button').removeClass('btn-danger').addClass('btn-outline-danger');

        // color the clicked one
        if (status == 'attended') {
            button.removeClass('btn-outline-success').addClass('btn-success');
        } else {
            button.removeClass('btn-outline-danger').addClass('btn-danger');
        }

        $('#counter').text(response.data.attended + ' of ' + response.data.total + ' arrived');
    }, 'json').fail(function () {
        alert('Could not reach the server. Please try again.');
    });
});
