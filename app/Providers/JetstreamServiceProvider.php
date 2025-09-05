<?php

namespace App\Providers;

use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\DeleteUser;
use App\Actions\Jetstream\InviteTeamMember;
use App\Actions\Jetstream\RemoveTeamMember;
use App\Actions\Jetstream\UpdateTeamName;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * ثبت هر گونه سرویس برنامه
     */
    public function register(): void
    {
        //
    }

    /**
     * راه‌اندازی هر گونه سرویس برنامه
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::createTeamsUsing(CreateTeam::class);
        Jetstream::updateTeamNamesUsing(UpdateTeamName::class);
        Jetstream::addTeamMembersUsing(AddTeamMember::class);
        Jetstream::inviteTeamMembersUsing(InviteTeamMember::class);
        Jetstream::removeTeamMembersUsing(RemoveTeamMember::class);
        Jetstream::deleteTeamsUsing(DeleteTeam::class);
        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * پیکربندی نقش‌ها و مجوزهایی که در برنامه در دسترس هستند
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::role('admin', 'مدیر', [
            'create',
            'read',
            'update',
            'delete',
        ])->description('کاربران مدیر می‌توانند هر عملیاتی را انجام دهند.');

        Jetstream::role('editor', 'ویرایشگر', [
            'read',
            'create',
            'update',
        ])->description('کاربران ویرایشگر توانایی خواندن، ایجاد و به‌روزرسانی را دارند.');
    }
}
