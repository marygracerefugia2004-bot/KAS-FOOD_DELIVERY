<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'href' => null,
    'icon' => 'fas fa-chart-simple',
    'value' => null,
    'label' => '',
    'iconOnly' => false,
    'delay' => 0,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'href' => null,
    'icon' => 'fas fa-chart-simple',
    'value' => null,
    'label' => '',
    'iconOnly' => false,
    'delay' => 0,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $tag = $href ? 'a' : 'div';
    $attributes = $href ? $attributes->merge(['href' => $href]) : $attributes;
    $animationStyle = $delay ? "animation-delay: {$delay}s;" : '';
?>

<<?php echo e($tag); ?> <?php echo e($attributes->merge(['class' => 'stat-card', 'style' => $animationStyle])); ?>>
    <div class="stat-card__icon">
        <i class="<?php echo e($icon); ?>"></i>
    </div>
    <div class="stat-card__content">
        <?php if(!$iconOnly): ?>
            <div class="stat-card__value"><?php echo e($value ?? '—'); ?></div>
        <?php endif; ?>
        <div class="stat-card__label"><?php echo e($label); ?></div>
    </div>
</<?php echo e($tag); ?>><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views/components/stat-card.blade.php ENDPATH**/ ?>