<?php
    $options = get_option(PLUGIN_KEY.'_configurations', true);
?>
    <div class="uh-main-page">
        <div class="page-content">
            <header class="pl-page-header">
                <h1><?= PLUGIN_NAME ?></h1>
            </header>
            <div class="pl-page-body">
                <div class="container-fluid">
                    <header class="form-header">
                        <h2>Configurations</h2>
                    </header>
                    <form id="pl_settings">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input pl-switch" name="email_confirmation" id="email_confirmation"
                                <?= ($options->email_confirmation === 'on') ? 'checked' : ''; ?> >
                            <label class="custom-control-label" for="email_confirmation"><?= __("Email Confirmation", 'wp_users_handler') ?></label>
                        </div>

                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input pl-switch" name="admin_approval" id="admin_approval"
                                <?= ($options->admin_approval === 'on') ? 'checked' : ''; ?> >
                            <label class="custom-control-label" for="admin_approval"><?= __("Admin approvals", 'wp_users_handler') ?></label>
                        </div>

                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input pl-switch" name="check_keyup" id="check_keyup"
                                <?= ($options->check_keyup === 'on') ? 'checked' : ''; ?> >
                            <label class="custom-control-label" for="check_keyup"><?= __("Automatically check on keyup", 'wp_users_handler') ?></label>
                        </div>

                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input pl-switch" name="block_users" id="block_users"
                                <?= ($options->block_users === 'on') ? 'checked' : ''; ?> >
                            <label class="custom-control-label" for="block_users"><?= __("Block users", 'wp_users_handler') ?></label>
                        </div>

                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input pl-switch" name="login_network" id="login_network"
                                <?= ($options->login_network === 'on') ? 'checked' : ''; ?> >
                            <label class="custom-control-label" for="login_network"><?= __("Limit login by IP Address", 'wp_users_handler') ?></label>
                        </div>

                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input pl-switch" name="limit_active_login" id="limit_active_login"
                                <?= ($options->limit_active_login === 'on') ? 'checked' : ''; ?> >
                            <label class="custom-control-label" for="limit_active_login"><?= __("Limit active login", 'wp_users_handler') ?></label>
                        </div>

                        <div class="pl-active-login <?= ($options->limit_active_login === 'off') ? 'hidden' : 'on' ?>">
                            <div class="form-group col-sm-3">
                                <label for="number_of_active_login">NO. of active login</label>
                                <input type="number" class="form-control pl-setting-input" name="number_of_active_login" id="number_of_active_login" aria-describedby="inputHelp" value="<?= $options->number_of_active_login ?>">
                                <small id="inputHelp" class="form-text text-muted"><?= __("Set the number of active login for every user.", 'wp_users_handler') ?></small>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
<?php