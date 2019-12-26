<?php
    /**
     * Created by PhpStorm.
     * User: Mustafa Shaaban
     * Date: 10/22/2019
     * Time: 12:54 AM
     */

    namespace UH\FORMS;

    use UH\FUNCTIONS\Wp_functions;
    use UH\HANDLER\Wp_users_handler;

    class Forms_controller
    {
        use Wp_functions;
        public static $instance;

        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         */
        public function __construct()
        {
            self::$instance = $this;
            $loader         = Wp_users_handler::get_instance()->get_loader();
            $loader->add_action($this->plugin_key().'_create_hidden_inputs', $this, 'create_hidden_inputs', 10, 1);
            $loader->add_action($this->plugin_key().'_create_nonce', $this, 'create_none', 10, 2);
        }

        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * The Form Controller
         * This function responsible fore controlling the form settings and create all form fields
         * by take them as an array and return the html form.
         *
         * @param array $form_fields
         *
         * @return bool|string
         */
        public function create_form($form_fields = [])
        {
            if (empty($form_fields)) {
                return false;
            }

            $settings = $this->sort_settings($form_fields);

            ob_start();
            $form = "";
            foreach ($settings as $key => $field) {
                if ($field['type'] === 'text' || $field['type'] === 'email' || $field['type'] === 'password' || $field['type'] === 'number') {
                    $form .= $this->std_inputs($field);
                } elseif ($field['type'] === 'file') {
                    $form .= $this->file_inputs($field);
                } elseif ($field['type'] === 'checkbox') {
                    $form .= $this->checkbox_inputs($field);
                } elseif ($field['type'] === 'radio') {
                    $form .= $this->radio_inputs($field);
                } elseif ($field['type'] === 'switch') {
                    $form .= $this->switch_inputs($field);
                } elseif ($field['type'] === 'textarea') {
                    $form .= $this->textarea_inputs($field);
                } elseif ($field['type'] === 'select') {
                    $form .= $this->selectBox_inputs($field);
                }
            }
            $form .= ob_get_clean();
            echo $form;
        }

        /**
         * This function responsible for create the start of tag form
         *
         * @param array $args [
         * class    => ''
         * id       => ''
         * ]
         *
         * @return false|string
         *
         */
        public function form_start($args = [])
        {
            $defaults = [
                'class' => '',
                'id'    => ''
            ];

            $input_data = array_merge($defaults, $args);

            ob_start();
            ?>
            <form action="" class="<?= $this->plugin_key() ?>_form <?= $input_data['class'] ?>" id="<?= $input_data['id'] ?>">
            <?php
            echo ob_get_clean();
        }

        /**
         * This function responsible for create the form submit button
         *
         * @param array $args [
         * value    => ''
         * class    => ''
         * id       => ''
         * before   => ''
         * after    => ''
         * ]
         *
         * @return false|string
         */
        public function form_submit_button($args = [])
        {
            $defaults = [
                'value'  => 'Submit',
                'class'  => '',
                'id'     => '',
                'before' => '',
                'after'  => '',
            ];

            $input_data = array_merge($defaults, $args);

            ob_start();
            ?>
            <div class="form-group">
                <?= $input_data['before'] ?>
                <button class="btn btn-primary pl-btn <?= $input_data['class'] ?>" id="<?= $input_data['id'] ?>" type="submit"><?= $input_data['value'] ?></button>
                <?= $input_data['after'] ?>
            </div>
            <?php
            echo ob_get_clean();
        }

        /**
         * This function responsible for create the end tag of form
         *
         * @return false|string
         */
        public function form_end()
        {
            ob_start();
            ?>
            </form>
            <?php
            echo ob_get_clean();
        }

        /**
         * This function responsible for create stander input field
         *
         * @param array $args [
         * type          => (text / email / password / number)
         * label         => ''
         * name          => ''
         * required      => ''
         * placeholder   => ''
         * class         => ''
         * id            => default is $this->plugin_key()_name
         * value         => ''
         * default_value => ''
         * visibility    => ''
         * before        => ''
         * after         => ''
         * autocomplete  => default on
         * hint          => ''
         * abbr          => ''
         * order         => 0
         * extra_attr    => [
         *   'maxlength' => '50',
         *   'minlength' => '',
         *   'max'       => '',
         *   'min'       => '',
         *   ]
         * ]
         *
         * @return false|string
         *
         */
        public function std_inputs($args = [])
        {
            ob_start();
            $defaults = [
                'type'          => 'text',
                'label'         => '',
                'name'          => '',
                'required'      => '',
                'placeholder'   => '',
                'class'         => '',
                'id'            => (empty($args['name'])) ? "" : $this->plugin_key().'_'.$args['name'],
                'value'         => '',
                'default_value' => '',
                'visibility'    => '',
                'before'        => '',
                'after'         => '',
                'autocomplete'  => 'on',
                'hint'          => '',
                'abbr'          => __("This field is required", "wp_users_handler"),
                'order'         => 0,
                'extra_attr'    => [
                    'maxlength' => '50',
                    'minlength' => '',
                    'max'       => '',
                    'min'       => '',
                ]
            ];

            $input_data = array_merge($defaults, $args);

            $require = (!empty($input_data['required'])) ? '<abbr class="required" title="'.$input_data['abbr'].'">*</abbr>' : '';
            $value   = (empty($input_data['default_value']) && $input_data['default_value'] !== 0) ? $input_data['value'] : $input_data['default_value'];;

            ?>
            <div class="form-group pl-input-wrapper <?= $input_data['class'] ?>">
                <?= $input_data['before'] ?>
                <label for="<?= $input_data['id'] ?>" class="pl-label"><?= $input_data['label'] ?></label>
                <?= $require ?>
                <input type="<?= $input_data['type'] ?>"
                       class="form-control pl-input"
                       id="<?= $input_data['id'] ?>"
                       name="<?= $input_data['name'] ?>"
                       value="<?= $value ?>"
                       autocomplete="<?= $input_data['autocomplete'] ?>"
                       placeholder="<?= $input_data['placeholder'] ?>"
                       aria-describedby="<?= $input_data['id']."_help" ?>"
                    <?= $this->create_attr($input_data) ?>
                    <?= $input_data['visibility'] ?>
                    <?= $input_data['required'] ?>>
                <?= $input_data['after'] ?>
                <?php
                    if (!empty($input_data['hint'])) {
                        ?>
                        <small id="<?= $input_data['id']."_help" ?>" class="form-text text-muted"><?= $input_data['hint'] ?></small><?php
                    }
                ?>
            </div>
            <?php

            return ob_get_clean();
        }

        /**
         * This function responsible for create the textare field
         *
         * @param $args [
         * label         => ''
         * name          => ''
         * required      => ''
         * placeholder   => ''
         * class         => ''
         * id            => default is $this->plugin_key()_name
         * value         => ''
         * before        => ''
         * after         => ''
         * autocomplete  => default on
         * rows          => '3'
         * hint          => ''
         * abbr          => ''
         * order         => 0
         * extra_attr    => []
         * ]
         *
         * @return false|string
         *
         */
        public function textarea_inputs($args = [])
        {
            ob_start();
            $defaults   = [
                'label'        => '',
                'name'         => '',
                'required'     => '',
                'placeholder'  => '',
                'class'        => '',
                'id'           => (empty($args['name'])) ? "" : $this->plugin_key().'_'.$args['name'],
                'value'        => '',
                'before'       => '',
                'after'        => '',
                'autocomplete' => 'on',
                'rows'         => '3',
                'hint'         => '',
                'abbr'         => __("This field is required", "wp_users_handler"),
                'order'        => 0,
                'extra_attr'   => []
            ];
            $input_data = array_merge($defaults, $args);
            $require    = (!empty($input_data['required'])) ? '<abbr class="required" title="'.$input_data['abbr'].'">*</abbr>' : '';
            ?>
            <div class="form-group pl-input-wrapper <?= $input_data['class'] ?>">
                <?= $input_data['before'] ?>
                <label for="<?= $input_data['id'] ?>" class="pl-label"><?= $input_data['label'] ?></label>
                <?= $require ?>
                <textarea class="form-control pl-textarea"
                          id="<?= $input_data['id'] ?>"
                          name="<?= $input_data['name'] ?>"
                          placeholder="<?= $input_data['placeholder'] ?>"
                          autocomplete="<?= $input_data['autocomplete'] ?>"
                          rows="<?= $input_data['rows'] ?>"
                          <?= $input_data['required'] ?> <?= $this->create_attr($input_data['extra_attr']) ?>><?= $input_data['value'] ?></textarea>
                <?= $input_data['after'] ?>
                <?php
                    if (!empty($input_data['hint'])) {
                        ?>
                        <small id="<?= $input_data['id']."_help" ?>" class="form-text text-muted"><?= $input_data['hint'] ?></small><?php
                    }
                ?>
            </div>
            <?php
            return ob_get_clean();
        }

        /**
         * This function responsible for create file input field
         *
         * @param array $args [
         * label        => ''
         * name         => ''
         * required     => ''
         * class        => ''
         * id           => default is $this->plugin_key()_name
         * before       => ''
         * after        => ''
         * hint         => ''
         * accept       => ''
         * multiple     => ''
         * abbr          => ''
         * order         => 0
         * extra_attr   => []
         * ]
         *
         * @return false|string
         *
         */
        public function file_inputs($args = [])
        {
            ob_start();
            $defaults = [
                'label'      => '',
                'name'       => '',
                'required'   => '',
                'class'      => '',
                'id'         => (empty($args['name'])) ? "" : $this->plugin_key().'_'.$args['name'],
                'before'     => '',
                'after'      => '',
                'hint'       => '',
                'accept'     => '',
                'multiple'   => '',
                'abbr'       => __("This field is required", "wp_users_handler"),
                'order'      => 0,
                'extra_attr' => []
            ];

            $input_data = array_merge($defaults, $args);
            $require    = (!empty($input_data['required'])) ? '<abbr class="required" title="'.$input_data['abbr'].'">*</abbr>' : '';

            ?>
            <div class="form-group pl-input-wrapper<?= $input_data['class'] ?>">
                <p><?= $input_data['label'] ?></p>
                <?= $require ?>
                <div class="custom-file">
                    <?= $input_data['before'] ?>
                    <input type="file"
                           class="custom-file-input pl-input"
                           id="<?= $input_data['id'] ?>"
                           name="<?= $input_data['name'] ?>"
                           aria-describedby="<?= $input_data['id']."_help" ?>"
                        <?= $this->create_attr($input_data) ?>
                        <?= $input_data['accept'] ?>
                        <?= $input_data['multiple'] ?>
                        <?= $input_data['required'] ?>>
                    <label class="custom-file-label" for="customFile"><?= $input_data['label'] ?></label>
                    <?= $input_data['after'] ?>
                </div>
                <?php
                    if (!empty($input_data['hint'])) {
                        ?>
                        <small id="<?= $input_data['id']."_help" ?>" class="form-text text-muted"><?= $input_data['hint'] ?></small><?php
                    }
                ?>
            </div>
            <?php

            return ob_get_clean();
        }

        /**
         * This function responsible for create selectBox input field
         *
         * @param array $args [
         * label          => ''
         * name           => ''
         * required       => ''
         * placeholder    => ''
         * options        => [option_value => option_title]
         * default_option => ''
         * select_option  => ''
         * class          => ''
         * id             => default is $this->plugin_key()_name
         * before         => ''
         * after          => ''
         * multiple       => ''
         * abbr          => ''
         * order         => 0
         * extra_attr     => []
         *
         * @return false|string
         *
         */
        public function selectBox_inputs($args = [])
        {
            ob_start();
            $defaults   = [
                'label'          => '',
                'name'           => '',
                'required'       => '',
                'placeholder'    => '',
                'options'        => [],
                'default_option' => '',
                'select_option'  => '',
                'class'          => '',
                'id'             => (empty($args['name'])) ? "" : $this->plugin_key().'_'.$args['name'],
                'before'         => '',
                'after'          => '',
                'multiple'       => '',
                'abbr'           => __("This field is required", "wp_users_handler"),
                'order'          => 0,
                'extra_attr'     => []
            ];
            $input_data = array_merge($defaults, $args);
            $require    = (!empty($input_data['required'])) ? '<abbr class="required" title="'.$input_data['abbr'].'">*</abbr>' : '';

            ?>
            <div class="form-group pl-input-wrapper <?= $input_data['class'] ?>">
                <?= $input_data['before'] ?>
                <label for="<?= $input_data['id'] ?>" class="pl-label"><?= $input_data['label'] ?></label>
                <?= $require ?>
                <select class="form-control pl-input" id="<?= $input_data['id'] ?>" name="<?= $input_data['name'] ?>" <?= $this->create_attr($input_data) ?> <?= $input_data['required'] ?>>
                    <?php
                        if (empty($input_data['default_option']) && empty($input_data['select_option'])) {
                            ?>
                            <option value="" disabled="disabled" selected><?= $input_data['placeholder'] ?></option> <?php
                        }
                        foreach ($input_data['options'] as $value => $title) {
                            ?>
                            <option value="<?= $value ?>" <?= (!empty($input_data['default_option']) && $input_data['default_option'] === $value) ? 'selected' : '' ?>><?= $title ?></option><?php
                        }
                    ?>
                </select>
                <?= $input_data['after'] ?>
            </div>
            <?php
            return ob_get_clean();
        }

        /**
         * This function responsible for create checkbox input field
         *
         * @param array $args [
         * type'    => 'checkbox',
         * choices' => array(
         *  array(
         *      label'      => ''
         *      name'       => ''
         *      required'   => ''
         *      class'      => ''
         *      id'         => default is $this->plugin_key()_name
         *      value'      => ''
         *      before'     => ''
         *      after'      => ''
         *      checked'    => ''
         *      abbr          => ''
         *      order         => 0
         *      extra_attr' => []
         *  )
         * )
         * order         => 0
         * ]
         *
         * @return false|string
         */
        public function checkbox_inputs($args = [])
        {
            ob_start();
            $defaults   = [
                'type'    => 'checkbox',
                'choices' => [
                    [
                        'label'      => '',
                        'name'       => '',
                        'required'   => '',
                        'class'      => '',
                        'id'         => '',
                        'value'      => '',
                        'before'     => '',
                        'after'      => '',
                        'checked'    => '',
                        'abbr'       => __("This field is required", "wp_users_handler"),
                        'order'      => 0,
                        'extra_attr' => []
                    ]
                ],
                'order'   => 0,
            ];
            $input_data = array_merge($defaults, $args);

            $count = 0;
            foreach ($input_data['choices'] as $name) {
                $require = (!empty($input_data['required'])) ? '<abbr class="required" title="'.$name['abbr'].'">*</abbr>' : '';
                if (empty($name['id'])) {
                    $id = (empty($name['name'])) ? "" : $this->plugin_key().'_'.str_replace('[]', '', $name['name']).$count;
                    $count++;
                } else {
                    $id = $name['id'];
                }
                ?>
                <div class="form-check pl-input-wrapper<?= $name['class'] ?>">
                    <?= $name['before'] ?>
                    <?= $require ?>
                    <input type="<?= $input_data['type'] ?>"
                           class="form-control pl-checkbox"
                           id="<?= $id ?>"
                           name="<?= $name['name'] ?>"
                           value="<?= $name['value'] ?>" <?= $name['required'] ?> <?= $this->create_attr($name['choices']) ?> <?= $name['checked'] ?>>
                    <label for="<?= $id ?>" class="pl-label"><?= $name['label'] ?></label>
                    <?= $name['after'] ?>
                </div>
                <?php
            }

            return ob_get_clean();
        }

        /**
         * This function responsible for create radio input field
         *
         * @param array $args [
         * type'    => 'checkbox',
         * name'    => ''
         * choices' => array(
         *  array(
         *      label'      => ''
         *      required'   => ''
         *      class'      => ''
         *      id'         => default is $this->plugin_key()_name
         *      value'      => ''
         *      before'     => ''
         *      after'      => ''
         *      checked'    => ''
         *      abbr          => ''
         *      order         => 0
         *      extra_attr' => []
         *  )
         * )
         * order         => 0
         * ]
         *
         * @return false|string
         */
        public function radio_inputs($args = [])
        {
            ob_start();
            $defaults   = [
                'type'    => 'radio',
                'name'    => '',
                'choices' => [
                    [
                        'label'      => '',
                        'required'   => '',
                        'class'      => '',
                        'id'         => (empty($args['choices']['name'])) ? "" : $this->plugin_key().'_'.$args['choices']['name'],
                        'value'      => '',
                        'before'     => '',
                        'after'      => '',
                        'checked'    => '',
                        'abbr'       => __("This field is required", "wp_users_handler"),
                        'order'      => 0,
                        'extra_attr' => []
                    ]
                ],
                'order'   => 0,
            ];
            $input_data = array_merge($defaults, $args);

            $count = 0;
            foreach ($input_data['choices'] as $name) {
                $require = (!empty($input_data['required'])) ? '<abbr class="required" title="'.$name['abbr'].'">*</abbr>' : '';
                if (empty($name['id'])) {
                    $id = (empty($name['name'])) ? "" : $this->plugin_key().'_'.str_replace('[]', '', $name['name']).$count;
                    $count++;
                } else {
                    $id = $name['id'];
                }
                ?>
                <div class="form-check pl-input-wrapper <?= $name['class'] ?>">
                    <?= $name['before'] ?>
                    <?= $require ?>
                    <input type="<?= $input_data['type'] ?>"
                           class="form-control pl-radio"
                           id="<?= $id ?>"
                           name="<?= $input_data['name'] ?>"
                           value="<?= $name['value'] ?>" <?= $name['required'] ?> <?= $this->create_attr($name['choices']) ?> <?= $name['checked'] ?>>
                    <label for="<?= $id ?>" class="pl-label"><?= $name['label'] ?></label>
                    <?= $name['after'] ?>
                </div>
                <?php
            }

            return ob_get_clean();
        }

        /**
         * This function responsible for create radio input field
         *
         * @param array $args [
         * type'       => 'switch',
         * label'      => ''
         * name'       => ''
         * required'   => ''
         * class'      => ''
         * id'         => default is $this->plugin_key()_name
         * before'     => ''
         * after'      => ''
         * checked'    => ''
         * abbr          => ''
         * order         => 0
         * extra_attr' => []
         * ]
         *
         * @return false|string
         */
        public function switch_inputs($args = [])
        {
            ob_start();
            $defaults   = [
                'type'       => 'switch',
                'label'      => '',
                'name'       => '',
                'required'   => '',
                'class'      => '',
                'id'         => (empty($args['name'])) ? "" : $this->plugin_key().'_'.$args['name'],
                'before'     => '',
                'after'      => '',
                'checked'    => '',
                'abbr'       => __("This field is required", "wp_users_handler"),
                'order'      => 0,
                'extra_attr' => []
            ];
            $input_data = array_merge($defaults, $args);
            $require    = (!empty($input_data['required'])) ? '<abbr class="required" title="'.$input_data['abbr'].'">*</abbr>' : '';

            ?>

            <div class="custom-control custom-switch pl-input-wrapper <?= $input_data['class'] ?>">
                <?= $input_data['before'] ?>
                <?= $require ?>
                <input type="checkbox"
                       class="custom-control-input pl-input pl-switch <?= $this->plugin_key().'-'.$input_data['class'] ?>"
                       id="<?= $input_data['id'] ?>"
                       name="<?= $input_data['name'] ?>"
                    <?= $input_data['required'] ?> <?= $this->create_attr($input_data) ?> <?= $input_data['checked'] ?>>
                <label class="custom-control-label" for="<?= $input_data['id'] ?>"><?= $input_data['label'] ?></label>
                <?= $input_data['after'] ?>
            </div>
            <?php
            return ob_get_clean();
        }

        /**
         * This function responsible for create extra html attributes.
         *
         * @param array $args
         *
         * @return string
         */
        public function create_attr($args = [])
        {
            $attrs = '';
            if (is_array($args['extra_attr']) && !empty($args['extra_attr'])) {
                foreach ($args['extra_attr'] as $name => $value) {

                    if (isset($args['type'])) {
                        if ($args['type'] === 'number' && $name === 'maxlength' || $name === 'minlength') {
                            continue;
                        }
                        if ($args['type'] !== 'number' && $name === 'max' || $name === 'min') {
                            continue;
                        }
                    }

                    $attrs .= " $name='$value' ";
                }
            }
            return $attrs;
        }

        /**
         * This function responsible for creating the input fielda
         *
         * @param array $args
         */
        public function create_hidden_inputs($args = [])
        {
            ob_start();
            $inputs = '';
            if (!empty($args)) {
                foreach ($args as $input) {
                    $name   = $input['name'];
                    $value  = $input['value'];
                    $inputs .= "<input type='hidden' name='$name' value='$value'/>";
                }
                $inputs .= ob_get_clean();
            }
            echo $inputs;
        }

        /**
         * This function responsible for creating the wp nonce
         *
         * @param $name
         * @param $value
         *
         * @return string
         */
        public function create_none($name, $value)
        {
            return wp_nonce_field($value, $name);
        }

        /**
         * This functions responsible for sort inputs
         *
         * @param $settings
         *
         * @return mixed
         */
        private function sort_settings($settings)
        {
            foreach ($settings as $key => $value) {
                if ($value['type'] === 'checkbox' && isset($value['choices']) && !empty($value['choices'])) {
                    $choices = $value['choices'];
                    usort($choices, function ($a, $b) {
                        return $a['order'] > $b['order'];
                    });
                    $settings[$key]['choices'] = $choices;
                }
            }
            usort($settings, function ($a, $b) {
                return $a['order'] > $b['order'];
            });

            return $settings;
        }
    }