<?php $this->Layout->css('/menu/css/sortable-menu.css'); ?>

<?php echo $this->Form->create(); ?>
    <!-- New Term -->
    <?php echo $this->Html->useTag('fieldsetstart', '<span id="toggle-add_new_term_fieldset" style="cursor:pointer;">' . __t('Add New Term') . '</span>'); ?>
        <div id="add_new_term_fieldset" class="horizontalLayout" style="display:none;">
            <?php echo $this->Form->input('Term.name', array('required' => 'required', 'type' => 'text', 'label' => __t('Name *'))); ?>
            <?php echo $this->Form->input('Term.parent_id', array('type' => 'select', 'label' => __t('Parent term'), 'options' => $parents, 'escape' => false, 'empty' => __t('-- None --'))); ?>
            <?php echo $this->Form->input(__t('Save'), array('type' => 'submit', 'label' => false)); ?>
        </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>

<?php if (!empty($results)): ?>
    <?php $this->Layout->script('/menu/js/nestedSortable/jquery-ui-1.8.11.custom.min.js'); ?>
    <?php $this->Layout->script('/menu/js/nestedSortable/jquery.ui.nestedSortable'); ?>
    <?php $this->Layout->script('/system/js/json.js'); ?>

    <div id="menu-sortContainer">
        <?php echo $this->Tree->generate($results, array('class' => 'sortable', 'plugin' => 'taxonomy', 'element' => 'term_node', 'id' => 'termsList', 'model' => 'Term', 'alias' => 'title')); ?>
    </div>

    <?php echo $this->Form->submit(__t('Save changes'), array('id' => 'saveChanges')); ?>
    <span id="saveStatus">&nbsp;</span>

    <script>
        $(document).ready(function() {
            $('ul.sortable').nestedSortable({
                listType: 'ul',
                disableNesting: 'no-nest',
                forcePlaceholderSize: true,
                handle: 'div',
                helper:    'clone',
                items: 'li',
                opacity: .6,
                placeholder: 'placeholder',
                revert: 250,
                tabSize: 25,
                tolerance: 'pointer',
                toleranceElement: '> div'
            });

            $('#saveChanges').click(function(e) {
                $('#saveStatus').text('<?php echo __t('Saving...'); ?>');
                arraied = $('ul.sortable').nestedSortable('toArray', {startDepthCount: 0});
                $.ajax({
                    type: 'POST',
                    url: QuickApps.settings.url,
                    data: 'data[Term][sorting]=' + $.toJSON(arraied),
                    success: function() {
                        $('#saveStatus').text('<?php echo __t('Saved!'); ?>');
                    }
                });
            });
        });
    </script>
<?php endif; ?>
<script>
    $("#toggle-add_new_term_fieldset").click(function () {
        $("#add_new_term_fieldset").toggle('fast', 'linear');
    });
</script>