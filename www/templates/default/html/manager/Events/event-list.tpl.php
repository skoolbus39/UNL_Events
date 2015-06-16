<label for="bulk_action">Bulk Actions</label>
<select id="bulk_action" class="all-pending-event-tools">
    <option value=""></option>
    <option value="move-to-upcoming">Move to Upcoming</option>
    <option value="move-to-upcoming">Move to Pending</option>
    <option value="recommend">Recommend</option>
    <option value="delete">Delete</option>
</select>

<table>
    <thead>
        <tr>
            <th>Select</th>
            <th>Title</th>
            <th>Date/Location</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($context as $event): ?>
            <?php
            $current_calendar_has_event = $controller->getCalendar()->hasEvent($event->getRawObject());
            ?>
            <tr>
                <td>
                    <input type="checkbox" id="event_select_<?php echo $event->id ?>" class="select-event" data-id="<?php echo $event->id; ?>">
                    <label for="event_select_<?php echo $event->id ?>">
                        Select this event
                    </label>
                </td>
                <td>
                    <a href="<?php echo $event->getEditURL($controller->getCalendar()) ?>"><?php echo $event->title; ?></a>
                </td>
                <td>
                    <ul>
                    <?php foreach($event->getDateTimes() as $datetime): ?>
                        <li>
                            <?php echo date('n/j/y @ h:ia', strtotime($datetime->starttime)); ?>
                            at <?php if (!empty($location = $datetime->getLocation())) echo $location->name; ?>
                         </li>
                    <?php endforeach; ?>
                    </ul>
                </td>
                <td>
                    <label for="event_action_<?php echo $event->id ?>">Select an option</label>
                    <select 
                        id="event_action_<?php echo $event->id ?>"
                        class="pending-event-tools" 
                        data-id="<?php echo $event->id; ?>"
                        data-recommend-url="<?php echo $event->getRecommendURL($controller->getCalendar()) ?>"
                        >
                        <option value="">Select an Action</option>
                        <?php if (!$current_calendar_has_event || $current_calendar_has_event->isPending()): ?>
                            <option value="move-to-upcoming">Move to Upcoming</option>
                        <?php endif; ?>
                        <?php if (!$current_calendar_has_event || $current_calendar_has_event->isApproved()): ?>
                            <option value="move-to-pending">Move to Pending</option>
                        <?php endif; ?>
                        <option value="recommend">Recommend</option>
                        <?php if ($current_calendar_has_event): ?>
                            <option value="delete">Delete</option>
                        <?php endif; ?>
                    </select>
                    <form id="delete-<?php echo $event->id; ?>" method="POST" action="<?php echo $event->getDeleteURL($controller->getCalendar()) ?>" class="delete-form hidden"></form>
                </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>