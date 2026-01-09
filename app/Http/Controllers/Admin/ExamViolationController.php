<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamViolation;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamViolationController extends Controller
{
    public function index(Request $request)
    {
        $violations = ExamViolation::with(['exam', 'examSession', 'student.classroom', 'forgivenByUser'])
            ->when($request->status, fn($q, $status) => $q->where('status', $status))
            ->when($request->search, fn($q, $search) => $q->whereHas('student', fn($sq) => $sq->where('name', 'like', "%{$search}%")->orWhere('nisn', 'like', "%{$search}%")))
            ->latest('violated_at')
            ->paginate(15);

        return inertia('Admin/ExamViolations/Index', [
            'violations' => $violations,
            'filters' => $request->only(['status', 'search']),
        ]);
    }

    public function show(ExamViolation $exam_violation)
    {
        $exam_violation->load(['exam.lesson', 'examSession', 'student.classroom', 'examGroup', 'forgivenByUser']);

        $grade = Grade::where('exam_id', $exam_violation->exam_id)
            ->where('exam_session_id', $exam_violation->exam_session_id)
            ->where('student_id', $exam_violation->student_id)
            ->first();

        return inertia('Admin/ExamViolations/Show', [
            'violation' => $exam_violation,
            'grade' => $grade,
        ]);
    }

    public function forgive(Request $request, ExamViolation $exam_violation)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        // Jika status active (siswa masih ujian) -> reset violation count
        if ($exam_violation->status === 'active') {
            $exam_violation->update([
                'violation_count' => 0,
                'status' => 'forgiven',
                'forgiven_by' => Auth::id(),
                'forgiven_at' => now(),
                'forgiven_reason' => $request->reason,
                'description' => 'Pelanggaran direset oleh admin: ' . $request->reason,
            ]);

            return redirect()
                ->route('admin.exam_violations.index')
                ->with('success', 'Pelanggaran siswa telah direset. Siswa dapat melanjutkan ujian.');
        }

        // Jika status ended (ujian diakhiri) -> izinkan lanjut ujian
        $exam_violation->update([
            'violation_count' => 0,
            'status' => 'forgiven',
            'forgiven_by' => Auth::id(),
            'forgiven_at' => now(),
            'forgiven_reason' => $request->reason,
        ]);

        // Reset end_time di grade agar siswa bisa lanjut
        $grade = Grade::where('exam_id', $exam_violation->exam_id)
            ->where('exam_session_id', $exam_violation->exam_session_id)
            ->where('student_id', $exam_violation->student_id)
            ->first();

        if ($grade && $grade->end_time !== null) {
            $grade->update([
                'end_time' => null,
            ]);
        }

        return redirect()
            ->route('admin.exam_violations.index')
            ->with('success', 'Siswa telah diizinkan untuk melanjutkan ujian.');
    }

    public function destroy(ExamViolation $exam_violation)
    {
        $exam_violation->delete();

        return redirect()
            ->route('admin.exam_violations.index')
            ->with('success', 'Data pelanggaran berhasil dihapus.');
    }
}
