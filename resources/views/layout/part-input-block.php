<?php

/* @var $input App\Core\Forms\Input */

$pib_required = ($input->getRequired()) ? ' <em class="required">*</em>' : '';
?>

    <div class="inputouterbox"<?php echo $input->getBlockWidthAttrib(); ?>> <!-- form input <?php echo e($input->getName()); ?>  -->

        <?php if($input->getLabel()): ?>

        <div class="labelbox">
            <label for="<?php echo e($input->getId()); ?>"><?php echo e($input->getLabel()); ?><?php echo $pib_required; ?></label>
        </div>
        <?php endif; ?>
        <div class="inputbox">
            <?php echo input($input); ?>

        </div>
        <?php
            if ($input->hasError()) {
                echo '<div class="inputerror">' . e($input->getError()) .'</div>';
            } else {
                 echo '<div class="inputerror" style="display:none;"></div>';
            }
        ?>

        <?php
            if ($input->getHelp()) {
                echo '<div class="helptext">' . e($input->getHelp()) . '</div>';
            }
        ?>

    </div> <!-- end of <?php echo e($input->getName()); ?> -->
