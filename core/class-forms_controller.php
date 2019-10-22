<?php
    /**
     * Created by PhpStorm.
     * User: Mustafa Shaaban
     * Date: 10/22/2019
     * Time: 12:54 AM
     */

    namespace UH\FORMS;

    use UH\HANDLER\Wp_users_handler;

    class Forms_controller
    {
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
            $loader->add_action(PLUGIN_KEY.'_create_hidden_inputs', $this, 'create_hidden_inputs', 10, 1);
            $loader->add_action(PLUGIN_KEY.'_create_nonce', $this, 'create_none', 10, 2);
        }

        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }




        /**
         * TODO:: Create The form controller and create the before and after do_actions, and create the fields data
         */
        public function lg_build_form_fields($att)
        {
            global $user_ID;
            $att = shortcode_atts(
                array(
                    'form_fields' => '',
                    'values'      => []
                ), $att, 'lg_form_fields');

            if (empty($att['form_fields'])) {
                return '';
            }

            if (@unserialize($att['form_fields']) !== false) {
                $form_fields = unserialize($att['form_fields']);
            } else {
                $form_fields = $att['form_fields'];
            }

            if (@unserialize($att['values']) !== false) {
                $form_values = unserialize($att['values']);
            } else {
                $form_values = $att['values'];
            }

            ob_start();
            $form = "";
            if (!empty($form_fields)) {
                foreach ($form_fields as $field) {
                    if ($field['type'] === 'select') {
                        $args = [
                            'label'          => __($field['label'], 'LG'),
                            'name'           => $field['name'],
                            'required'       => ($field['required'] === 1) ? 'required' : '',
                            'placeholder'    => __($field['label'], 'LG'),
                            'id'             => "LG_".$field['name'],
                            'options'        => $field['choices'],
                            'default_option' => $field['default_value'][0]
                        ];
                        $form .= $this->lg_build_selectbox($args);
                    } elseif ($field['type'] === 'text' || $field['type'] === 'email' || $field['type'] === 'file' || $field['type'] === 'number') {
                        switch ($field['name']) {
                            case 'email_address':
                                $value = get_user_meta($user_ID, 'billing_email', true);
                                break;
                            case 'phone_number':
                                $value = get_user_meta($user_ID, 'billing_phone', true);
                                break;
                            case 'company_name':
                                $value = get_user_meta($user_ID, 'billing_company', true);
                                break;
                            default:
                                $value = (array_key_exists($field['name'], $form_values)) ? $form_values[$field['name']] : '';
                                break;
                        }
                        $args = [
                            'type'          => $field['type'],
                            'label'         => __($field['label'], 'LG'),
                            'name'          => $field['name'],
                            'value'         => $value,
                            'default_value' => $field['default_value'],
                            'required'      => ($field['required'] === 1) ? 'required' : '',
                            'placeholder'   => __($field['label'], 'LG'),
                            'id'            => "LG_".$field['name']
                        ];
                        $form .= $this->lg_build_input_default($args);
                    } elseif ($field['type'] === 'textarea') {
                        $args = [
                            'label'       => __($field['label'], 'LG'),
                            'name'        => $field['name'],
                            'value'       => (array_key_exists($field['name'], $form_values)) ? $form_values[$field['name']] : '',
                            'required'    => ($field['required'] === 1) ? 'required' : '',
                            'placeholder' => __($field['label'], 'LG'),
                            'id'          => "LG_".$field['name'],
                        ];
                        $form .= $this->lg_build_textarea($args);
                    } elseif ($field['type'] === 'checkbox') {
                        $args = [
                            'type'     => $field['type'],
                            'label'    => __($field['label'], 'LG'),
                            'name'     => $field['name'],
                            'value'    => $field['default_value'],
                            'choices'  => $field['choices'],
                            'required' => ($field['required'] === 1) ? 'required' : '',
                            'id'       => "LG_".$field['name']
                        ];
                        $form .= $this->lg_build_checkbox($args);
                    } else {
                        $args = [
                            'type'        => $field['type'],
                            'label'       => __($field['label'], 'LG'),
                            'name'        => $field['name'],
                            'required'    => ($field['required'] === 1) ? 'required' : '',
                            'placeholder' => __($field['label'], 'LG'),
                            'id'          => "LG_".$field['name']
                        ];
                        $form .= $this->lg_build_input_default($args);
                    }

                }
            }
            $form .= ob_get_clean();

            return $form;
        }



        /**
         * This function responsible for create the start of tag form
         *
         * @param $args [
         * class    => ''
         * id       => ''
         * ]
         *
         * @return false|string
         *
         */
        public function form_start($args)
        {
            $defaults = [
                'class' => '',
                'id'    => ''
            ];

            $input_data = array_merge($defaults, $args);

            ob_start();
            ?>
            <form action="" class="<?= PLUGIN_KEY ?>_form <?= $input_data['class'] ?>" id="<?= $input_data['id'] ?>">
            <?php
            return ob_get_clean();
        }

        /**
         * This function responsible for create the form submit button
         *
         * @param $args [
         * value    => ''
         * class    => ''
         * id       => ''
         * before   => ''
         * after    => ''
         * ]
         *
         * @return false|string
         */
        public function form_submit_button($args)
        {
            $defaults = [
                'value'  => '',
                'class'  => '',
                'id'     => '',
                'before' => '',
                'after'  => '',
            ];

            $input_data = array_merge($defaults, $args);

            ob_start();
            ?>
            <?= $input_data['before'] ?>
            <button class="btn btn-primary pl-btn <?= $input_data['class'] ?>" id="<?= $input_data['id'] ?>" type="submit"><?= $input_data['value'] ?></button>
            <?= $input_data['after'] ?>
            <?php
            return ob_get_clean();
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
            return ob_get_clean();
        }


        /**
         * This function responsible for create stander input field
         *
         * @param array $args [
         * type          => (text / email / number)
         * label         => ''
         * name          => ''
         * required      => ''
         * placeholder   => ''
         * class         => ''
         * id            => default is PLUGIN_KEY_name
         * value         => ''
         * default_value => ''
         * visibility    => ''
         * before        => ''
         * after         => ''
         * autocomplete  => default on
         * hint          => ''
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
                'id'            => (empty($args['name'])) ? "" : PLUGIN_KEY.'_'.$args['name'],
                'value'         => '',
                'default_value' => '',
                'visibility'    => '',
                'before'        => '',
                'after'         => '',
                'autocomplete'  => 'on',
                'hint'          => '',
                'extra_attr'    => [
                    'maxlength' => '50',
                    'minlength' => '',
                    'max'       => '',
                    'min'       => '',
                ]
            ];

            $input_data = array_merge($defaults, $args);

            $require = (!empty($input_data['required'])) ? '<abbr class="required" title="required">*</abbr>' : '';
            $value   = (empty($input_data['default_value']) && $input_data['default_value'] !== 0) ? $input_data['value'] : $input_data['default_value'];;

            ?>
            <div class="form-group <?= $input_data['class'] ?>">
                <?= $input_data['before'] ?>
                <label for="<?= $input_data['id'] ?>"><?= $input_data['label'] ?></label>
                <?= $require ?>
                <input type="<?= $input_data['type'] ?>"
                       class="form-control"
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
         * id            => default is PLUGIN_KEY_name
         * value         => ''
         * before        => ''
         * after         => ''
         * autocomplete  => default on
         * rows          => '3'
         * hint          => ''
         * extra_attr    => []
         * ]
         *
         * @return false|string
         *
         */
        public function create_textarea($args = [])
        {
            ob_start();
            $defaults   = [
                'label'        => '',
                'name'         => '',
                'required'     => '',
                'placeholder'  => '',
                'class'        => '',
                'id'           => (empty($args['name'])) ? "" : PLUGIN_KEY.'_'.$args['name'],
                'value'        => '',
                'before'       => '',
                'after'        => '',
                'autocomplete' => 'on',
                'rows'         => '3',
                'hint'         => '',
                'extra_attr'   => []
            ];
            $input_data = array_merge($defaults, $args);
            $require    = (!empty($input_data['required'])) ? '<abbr class="required" title="required">*</abbr>' : '';
            ?>
            <div class="form-group <?= $input_data['class'] ?>">
                <?= $input_data['before'] ?>
                <label for="<?= $input_data['id'] ?>"><?= $input_data['label'] ?></label>
                <?= $require ?>
                <textarea class="form-control"
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
         * id           => default is PLUGIN_KEY_name
         * before       => ''
         * after        => ''
         * hint         => ''
         * accept       => ''
         * multiple     => ''
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
                'id'         => (empty($args['name'])) ? "" : PLUGIN_KEY.'_'.$args['name'],
                'before'     => '',
                'after'      => '',
                'hint'       => '',
                'accept'     => '',
                'multiple'   => '',
                'extra_attr' => []
            ];

            $input_data = array_merge($defaults, $args);
            $require    = (!empty($input_data['required'])) ? '<abbr class="required" title="required">*</abbr>' : '';

            ?>
            <div class="form-group <?= $input_data['class'] ?>">
                <p><?= $input_data['label'] ?></p>
                <?= $require ?>
                <div class="custom-file">
                    <?= $input_data['before'] ?>
                    <input type="file"
                           class="custom-file-input"
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
         * id             => default is PLUGIN_KEY_name
         * before         => ''
         * after          => ''
         * multiple       => ''
         * extra_attr     => []
         *
         * @return false|string
         *
         */
        public function create_selectBox($args = [])
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
                'id'             => (empty($args['name'])) ? "" : PLUGIN_KEY.'_'.$args['name'],
                'before'         => '',
                'after'          => '',
                'multiple'       => '',
                'extra_attr'     => []
            ];
            $input_data = array_merge($defaults, $args);
            $require    = (!empty($input_data['required'])) ? '<abbr class="required" title="required">*</abbr>' : '';

            ?>
            <div class="form-group <?= $input_data['class'] ?>">
                <?= $input_data['before'] ?>
                <label for="<?= $input_data['id'] ?>"><?= $input_data['label'] ?></label>
                <?= $require ?>
                <select class="form-control" id="<?= $input_data['id'] ?>" name="<?= $input_data['name'] ?>" <?= $this->create_attr($input_data) ?> <?= $input_data['required'] ?>>
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
         * TODO:: enhancement the function, and create the switches and radio buttons inputs
        */
        public function checkbox_inputs($args = [])
        {
            ob_start();
            $defaults   = [
                'type'       => 'checkbox',
                'label'      => '',
                'name'       => '',
                'required'   => '',
                'class'      => '',
                'id'         => (empty($args['name'])) ? "" : PLUGIN_KEY.'_'.$args['name'],
                'value'      => '',
                'choices'    => [],
                'before'     => '',
                'after'      => '',
                'extra_attr' => []
            ];
            $input_data = array_merge($defaults, $args);
            $require    = (!empty($input_data['required'])) ? '<abbr class="required" title="required">*</abbr>' : '';

            foreach ($input_data['choices'] as $name => $value) {
                ?>
                <div class="form-check <?= $input_data['class'] ?>">
                    <?= $input_data['before'] ?>
                    <?= $require ?>
                    <input type="<?= $input_data['type'] ?>"
                           class="form-control"
                           id="<?= $input_data['id'] ?>"
                           name="<?= $name ?>"
                           value="<?= $name ?>" <?= $input_data['required'] ?> <?= $this->create_attr($input_data) ?> <?= ($input_data['value'][0] === $name) ? 'checked' : '' ?>>
                    <label for="<?= $input_data['id'] ?>"><?= $value ?></label>
                    <?= $input_data['after'] ?>
                </div>
                <?php
            }

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
                    if ($args['type'] === 'number' && $name === 'maxlength' || $name === 'minlength') {
                        continue;
                    }
                    if ($args['type'] !== 'number' && $name === 'max' || $name === 'min') {
                        continue;
                    }
                    $attrs .= " $name='$value' ";
                }
            }
            return $attrs;
        }

        /**
         * This function responsible for creating the input fielda
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
    }