<?php if (isset($context->eventdatetime->location_id) && $context->eventdatetime->location_id): ?>
<?php $l = $context->eventdatetime->getLocation(); ?>
<?php if (isset($l->mapurl) || !empty($l->name)): ?>
    <span class="location">
        <span class="eventicon-location" aria-hidden="true"></span><span class="wdn-text-hidden">Location:</span>
<?php if (isset($l->mapurl)): ?>
        <a class="mapurl" href="<?php echo $l->mapurl ?>"><?php echo $l->name; ?></a>
<?php else: ?>
        <?php echo $l->name; ?>
<?php endif; ?>
    </span>
<?php endif; ?>
<?php endif; ?>
