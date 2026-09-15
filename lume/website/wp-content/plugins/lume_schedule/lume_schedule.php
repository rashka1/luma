<?php
/*
Plugin Name: Lume Schedule
Description: Shows the classes from the Lume admin panel on the website. Put [lume_schedule] or [lume_next_classes] in a page.
Version: 1.0
Author: Lume Pilates Studio
*/

// classes and bookings are in lume_db too


// [lume_schedule] = table with the classes of the next 14 days
function lume_schedule_shortcode() {
    global $wpdb;

    $today = current_time('Y-m-d');
    $last_day = date('Y-m-d', strtotime($today . ' +14 days'));

    $classes = $wpdb->get_results($wpdb->prepare(
        "SELECT classes.*,
            (SELECT COUNT(*) FROM bookings WHERE bookings.class_id = classes.id) AS booked
        FROM classes
        WHERE class_date >= %s AND class_date <= %s
        ORDER BY class_date, start_time",
        $today, $last_day
    ));

    if (count($classes) == 0) {
        return '<p>No classes in the next two weeks.</p>';
    }

    // wordpress table style
    wp_enqueue_style('wp-block-table');

    $html = '<figure class="wp-block-table is-style-stripes"><table>';
    $html .= '<thead><tr><th>Date</th><th>Time</th><th>Class</th><th>Instructor</th><th>Places left</th></tr></thead><tbody>';

    foreach ($classes as $class) {
        $places = $class->capacity - $class->booked;

        $places_text = $places;
        if ($places <= 0) {
            $places_text = 'Full';
        }

        $html .= '<tr>';
        $html .= '<td style="white-space: nowrap">' . esc_html(date('D d M', strtotime($class->class_date))) . '</td>';
        $html .= '<td style="white-space: nowrap">' . esc_html(date('h:i A', strtotime($class->start_time))) . '</td>';
        $html .= '<td>' . esc_html($class->class_name) . '</td>';
        $html .= '<td>' . esc_html($class->instructor) . '</td>';
        $html .= '<td>' . esc_html($places_text) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table></figure>';
    return $html;
}
add_shortcode('lume_schedule', 'lume_schedule_shortcode');


// [lume_next_classes] = the next 3 classes
function lume_next_classes_shortcode() {
    global $wpdb;

    $today = current_time('Y-m-d');

    $classes = $wpdb->get_results($wpdb->prepare(
        "SELECT classes.*,
            (SELECT COUNT(*) FROM bookings WHERE bookings.class_id = classes.id) AS booked
        FROM classes
        WHERE class_date >= %s
        ORDER BY class_date, start_time
        LIMIT 3",
        $today
    ));

    if (count($classes) == 0) {
        return '<p>No classes planned yet.</p>';
    }

    $html = '<ul>';
    foreach ($classes as $class) {
        $places = $class->capacity - $class->booked;

        $html .= '<li><strong>' . esc_html($class->class_name) . '</strong> - ';
        $html .= esc_html(date('D d M', strtotime($class->class_date))) . ' at ';
        $html .= esc_html(date('h:i A', strtotime($class->start_time))) . ' with ';
        $html .= esc_html($class->instructor) . ' (' . esc_html($places) . ' places left)</li>';
    }
    $html .= '</ul>';

    return $html;
}
add_shortcode('lume_next_classes', 'lume_next_classes_shortcode');


// no emoji (offline)
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
