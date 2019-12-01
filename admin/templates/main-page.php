<?php
    $options = get_option($this->plugin_key().'_configurations', true);
?>
    <div class="uh-main-page">
        <div class="overlay">
            <div class="page-content">
                <ul class="tabs tabs-fixed-width z-depth-1">
                    <li class="tab"><a href="#welcome"><?= __("Welcome", 'wp_users_handler') ?></a></li>
                    <li class="tab"><a class="active" href="#configuration"><?= __("Configurations", 'wp_users_handler') ?></a></li>
                    <li class="tab"><a href="#documentation"><?= __("Documentation", 'wp_users_handler') ?></a></li>
                    <li class="tab"><a href="#store"><?= __("Store", 'wp_users_handler') ?></a></li>
                    <li class="tab"><a href="#contact"><?= __("Contact Us", 'wp_users_handler') ?></a></li>
                </ul>
                <div id="welcome" class="col s12"></div>
                <div id="configuration" class="col s12">
                    <div class="container-fluid">
                        <form id="pl_settings">

                            <div class="switch">
                                <label>
                                    <input class="pl-switch" type="checkbox" name="email_confirmation" <?= ($options->email_confirmation === 'on') ? 'checked' : ''; ?> >
                                    <span class="lever"></span>
                                    <?= __("Email Confirmation", 'wp_users_handler') ?>
                                </label>
                            </div>

                            <div class="switch">
                                <label>
                                    <input class="pl-switch" type="checkbox" name="admin_approval" <?= ($options->admin_approval === 'on') ? 'checked' : ''; ?> >
                                    <span class="lever"></span>
                                    <?= __("Admin approvals", 'wp_users_handler') ?>
                                </label>
                            </div>

                            <div class="switch">
                                <label>
                                    <input class="pl-switch" type="checkbox" name="check_keyup" <?= ($options->check_keyup === 'on') ? 'checked' : ''; ?> >
                                    <span class="lever"></span>
                                    <?= __("Automatically check on keyup", 'wp_users_handler') ?>
                                </label>
                            </div>

                            <div class="switch">
                                <label>
                                    <input class="pl-switch" type="checkbox" name="block_users" <?= ($options->block_users === 'on') ? 'checked' : ''; ?> >
                                    <span class="lever"></span>
                                    <?= __("Block users", 'wp_users_handler') ?>
                                </label>
                            </div>

                            <div class="switch">
                                <label>
                                    <input class="pl-switch" type="checkbox" name="login_network" <?= ($options->login_network === 'on') ? 'checked' : ''; ?> >
                                    <span class="lever"></span>
                                    <?= __("Limit login by IP Address", 'wp_users_handler') ?>
                                </label>
                            </div>

                            <div class="switch">
                                <label>
                                    <input class="pl-switch" type="checkbox" name="limit_active_login" <?= ($options->limit_active_login === 'on') ? 'checked' : ''; ?> >
                                    <span class="lever"></span>
                                    <?= __("Limit active login", 'wp_users_handler') ?>
                                </label>
                            </div>

                            <div class="pl-active-login <?= ($options->limit_active_login === 'off') ? 'hidden' : 'on' ?>">
                                <div class="row">
                                    <div class="input-field col s3">
                                        <input class="pl-setting-input" id="number_of_active_login" name="number_of_active_login" type="number" value="<?= $options->number_of_active_login ?>">
                                        <label for="number_of_active_login"><?= __("No. of active login", 'wp_users_handler') ?> </label>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div id="documentation" class="col s12"></div>
                <div id="store" class="col s12"></div>
                <div id="contact" class="col s12"></div>
            </div>

        </div>
    </div>
<?php