<?php

namespace App\Providers;

use App\Models\Faculty;
use App\Models\Grade;
use App\Models\SectionSubject;
use App\Policies\FacultyPolicy;
use App\Policies\GradePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Grade::class, GradePolicy::class);
        Gate::policy(Faculty::class, FacultyPolicy::class);

        // Register the encodeForSectionSubject gate
        Gate::define('encodeForSectionSubject', function ($user, SectionSubject $sectionSubject) {
            return (new GradePolicy)->encodeForSectionSubject($user, $sectionSubject);
        });
    }
}
