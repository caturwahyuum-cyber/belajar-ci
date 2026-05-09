<?= $this->extend('layout'); ?>
<?= $this->section('content'); ?>

<table class="table">
    <tr>
        <td width="200">Username</td>
        <td>: <?= $username; ?></td>
    </tr>
    <tr>
        <td>Role</td>
        <td>:
            <span class="badge <?= ($role == 'admin') ? 'bg-danger' : 'bg-primary'; ?>">
                <?= $role; ?>
            </span>
        </td>
    </tr>
    <tr>
        <td>Email</td>
        <td>: <?= $email; ?></td>
    </tr>
    <tr>
        <td>Login Time</td>
        <td>: <?= $login_time; ?></td>
    </tr>
    <tr>
        <td>Status</td>
        <td>: <span class="badge bg-success"><?= $status; ?></span></td>
    </tr>
</table>

<?= $this->endSection(); ?>