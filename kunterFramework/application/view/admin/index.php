<div class="container">
    <h1>Admin/index</h1>

    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>

        <h3>What happens here ?</h3>

        <?php
        $availableAccountTypes = UserModel::getAvailableAccountTypes();
        ?>

        <div>
            This controller/action/view shows a list of all users in the system. with the ability to soft delete a user
            or suspend a user.
        </div>
        <div>
            <table class="overview-table js-table">
>
                <thead>
                    <tr>
                        <td>Id</td>
                        <td>Avatar</td>
                        <td>Username</td>
                        <td>User's email</td>
                        <td>Activated ?</td>
                        <td>Link to user's profile</td>
                        <td>Account Type</td>
                        <td>suspension Time in days</td>
                        <td>Soft delete</td>
                        <td>Submit</td>
                    </tr>
                </thead>
                <?php foreach ($this->users as $user) { ?>
                    <tr class="<?= ($user->user_active == 0 ? 'inactive' : 'active'); ?>">
                        <td><?= $user->user_id; ?></td>
                        <td class="avatar">
                            <?php if (isset($user->user_avatar_link)) { ?>
                                <img src="<?= $user->user_avatar_link; ?>" />
                            <?php } ?>
                        </td>
                        <td><?= $user->user_name; ?></td>
                        <td><?= $user->user_email; ?></td>
                        <td><?= ($user->user_active == 0 ? 'No' : 'Yes'); ?></td>
                        <td>
                            <a href="<?= Config::get('URL') . 'profile/showProfile/' . $user->user_id; ?>">Profile</a>
                        </td>
                        <form action="<?= config::get("URL"); ?>admin/actionAccountSettings" method="post">
                            <td>
                                <select name="account_type" id="account_type">
                                    <?php foreach ($availableAccountTypes as $type): ?>
                                        <?php if ($type->group_id == $user->account_type): ?>
                                            <option value="<?= $type->group_id ?>" selected="selected">
                                                <?= $type->group_name; ?>
                                            </option>
                                        <?php else: ?>
                                            <option value="<?= $type->group_id ?>">
                                                <?= $type->group_name; ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </td>

                            <td><input type="number" name="suspension" /></td>
                            <td><input type="checkbox" name="softDelete" <?php if ($user->user_deleted) { ?> checked <?php } ?> /></td>
                            <td>
                                <input type="hidden" name="user_id" value="<?= $user->user_id; ?>" />
                                <input type="submit" />
                            </td>
                        </form>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>