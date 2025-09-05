<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class StudentDashboard extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->studentProfile;
        $studentProgress = $user->studentProgress;
        $totalUsers = User::count();

        if($user->studentProfile->is_child){

          return Inertia::render('Kid_Student/Dashboard', [
              'student' => $student,
              'studentProgress' => $studentProgress,
              'quickActions' => $this->getQuickActions(),
              'activeCourses' => $this->getActiveCourses($user),
              'dailyChallenge' => $this->getDailyChallenge($user),
              'learningStats' => $this->getLearningStats($user),
              'userLevel' => $this->calculateUserLevel($user),
              'xp' => $this->calculateUserXP($user),
              'weeklyStudyMinutes' => $this->calculateWeeklyStudyMinutes($user),
              'totalUsers' => $totalUsers,

          ]);
        }
        else {
          return Inertia::render('Student/Dashboard', [
              'student' => $student,
              'studentProgress' => $studentProgress,
              'quickActions' => $this->getQuickActions(),
              'activeCourses' => $this->getActiveCourses($user),
              'dailyChallenge' => $this->getDailyChallenge($user),
              'learningStats' => $this->getLearningStats($user),
              'userLevel' => $this->calculateUserLevel($user),
              'xp' => $this->calculateUserXP($user),
              'weeklyStudyMinutes' => $this->calculateWeeklyStudyMinutes($user),
              'totalUsers' => $totalUsers,
          ]);
        }
    }

    protected function getQuickActions()
    {
        return [
            [
                'icon' => 'play',
                'title' => 'دوره ها',
                'subtitle' => 'دوره فعلی خود را ادامه دهید',
                'bgColor' => 'bg-indigo-500',
            ],
            [
                'icon' => 'plus-circle',
                'title' => 'آزمون ها',
                'subtitle' => 'آزمون بدهید و سطح خود را بسنجید',
                'bgColor' => 'bg-green-500',
            ],
            [
                'icon' => 'microphone',
                'title' => 'کلمات ذخیره شده',
                'subtitle' => 'کلمات ذخیره شده خود را ببینید',
                'bgColor' => 'bg-amber-500',
            ],
            [
                'icon' => 'book-open',
                'title' => 'پروفایل',
                'subtitle' => 'پروفایل خود را به سلیقه خودتان ادیت کنید',
                'bgColor' => 'bg-purple-500',
            ],
        ];
    }

    protected function getActiveCourses($user)
    {
        return $user->courses()
            ->with('teacher')
            ->orderByDesc('course_user.updated_at')
            ->limit(3)
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'teacher' => $course->teacher->name,
                    'thumbnail' => $course->thumbnail,
                    'progress' => $course->pivot->progress,
                ];
            });
    }

    protected function getDailyChallenge($user)
    {
        return [
            'title' => '10 کلمه جدید امروز',
            'description' => 'این 10 کلمه را یاد بگیرید و 50 امتیاز کسب کنید!',
            'timeLeft' => '15 دقیقه باقی مانده',
            'wordsCount' => 10,
            'points' => 50,
        ];
    }

    protected function getLearningStats($user)
    {
        $stats = $user->learningStat;

        return [
            'learnedWords' => $stats->learned_words_count ?? 0,
            'activeDays' => $stats->active_days_streak ?? 0,
            'studyTime' => $this->formatStudyTime($stats->total_study_minutes ?? 0),
            'rank' => $stats->rank ?? 0,
            'totalUsers' => 5000,
        ];
    }

    protected function calculateUserLevel($user)
    {
        $progress = $user->studentProgress->first();

        if (! $progress) {
            return 'A1';
        }

        $points = $progress->level;

        if ($points >= 5) {
            return 'C2';
        }
        if ($points >= 4) {
            return 'C1';
        }
        if ($points >= 3) {
            return 'B2';
        }
        if ($points >= 2) {
            return 'B1';
        }
        if ($points >= 1) {
            return 'A2';
        }

        return 'A1';
    }

    protected function calculateUserXP($user)
    {
        $progress = $user->studentProgress->first();

        if (! $progress) {
            return 0;
        }

        $xp = $progress->xp;

        return $xp;
    }

    protected function calculateWeeklyStudyMinutes($user)
    {
        $learningStat = $user->learningStat;

        if (! $learningStat) {
            return 0;
        }

        $weeklyProgress = $learningStat->weekly_progress ?? [];
        $total = 0;

        // هفته جاری
        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');

        foreach ($weeklyProgress as $week => $minutes) {
            if ($week === $startOfWeek) {
                $total += $minutes;
            }
        }

        return $total;
    }

    protected function formatStudyTime($minutes)
    {
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        return "{$hours}h {$remainingMinutes}m";
    }

    public function profile(): Response
    {
        $user = Auth::user();
        $profile = $user->studentProfile;
        $studentProgress = $user->studentProgress;

        if($user->studentProfile->is_child){

          return inertia('Kid_Student/Profile', [
              'user' => $user,
              'studentProfile' => $profile,
              'studentProgress' => $studentProgress,
          ]);
        }
        else {
          return inertia('Student/Profile', [
              'user' => $user,
              'studentProfile' => $profile,
              'studentProgress' => $studentProgress,
          ]);
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'learning_languages' => 'nullable|array',
            'known_languages' => 'nullable|array',
            'education_level' => 'nullable|string|max:100',
            'occupation' => 'nullable|string|max:100',
            'learning_goals' => 'nullable|string',
            'preferred_learning_style' => 'nullable|string|max:100',
            'daily_study_time' => 'nullable|integer|min:0',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_profile_photo' => 'nullable|boolean',
        ]);


        // مدیریت تصویر پروفایل
        if ($validated['remove_profile_photo'] ?? false) {
            $this->removeProfilePhoto($user);
        }

        if ($request->hasFile('profile_photo')) {
            $this->updateProfilePhoto($user, $request->file('profile_photo'));
        }

        // آپدیت اطلاعات کاربر
        $user->update(['name' => $validated['name']]);

        // آپدیت پروفایل دانشجو
        $user->studentProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only([
                'bio', 'phone', 'country', 'learning_languages',
                'known_languages', 'education_level', 'occupation',
                'learning_goals', 'preferred_learning_style', 'daily_study_time',
            ])
        );

        return redirect()->back()->with([
            'success' => 'پروفایل با موفقیت بروزرسانی شد',
            'user' => $user->fresh()
        ]);
    }

    protected function removeProfilePhoto($user)
    {
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            $user->forceFill(['profile_photo_path' => null])->save();
        }
    }

    protected function updateProfilePhoto($user, $photo)
    {
        // حذف تصویر قبلی
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // ذخیره تصویر جدید
        $path = $photo->store('student-profile-photos', 'public');
        $user->forceFill(['profile_photo_path' => $path])->save();
    }

    public function Leaderboard()
    {

        $user = Auth::user();
        // دریافت کاربران به همراه اطلاعات LearningStat و StudentProgress
        $users = User::query()
            ->with(['learningStat', 'studentProgress'])
            ->get()
            ->map(function ($user) {
              $progress = $user->studentProgress->first();
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_photo_url' => $user->profile_photo_url,
                    'rank' => $user->learningStat->rank ?? 0,
                    'active_streak' => $user->learningStat->active_days_streak ?? 0,
                    'total_study_minutes' => $user->learningStat->total_study_minutes ?? 0,
                    'xp' => $progress->xp ?? 0,
                    'level' => $progress->level ?? 1,
                    'lessons_completed' => $progress->lessons_completed ?? 0,
                ];
            })
            ->sortByDesc('xp') // مرتب‌سازی بر اساس رتبه
            ->values()
            ->toArray();

        if($user->studentProfile->is_child){

          return Inertia::render('Kid_Student/Leaderboard', [
              'users' => $users,
          ]);
        }
        else {
          return Inertia::render('Student/Leaderboard', [
              'users' => $users,
          ]);
        }
    }
}
