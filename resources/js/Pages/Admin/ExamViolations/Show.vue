<template>
  <Head>
    <title>Detail Pelanggaran - Aplikasi Ujian Online</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-12">
        <Link href="/admin/exam-violations" class="btn btn-secondary mb-3">
          <i class="fa fa-arrow-left"></i> Kembali
        </Link>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="card border-0 shadow mb-4">
          <div class="card-header">
            <h5 class="mb-0"><i class="fa fa-user"></i> Informasi Siswa</h5>
          </div>
          <div class="card-body">
            <table class="table table-borderless">
              <tbody>
                <tr>
                  <td width="40%"><strong>Nama</strong></td>
                  <td>{{ violation.student.name }}</td>
                </tr>
                <tr>
                  <td><strong>NISN</strong></td>
                  <td>{{ violation.student.nisn }}</td>
                </tr>
                <tr>
                  <td><strong>Kelas</strong></td>
                  <td>{{ violation.student.classroom?.title }}</td>
                </tr>
                <tr>
                  <td><strong>Jenis Kelamin</strong></td>
                  <td>{{ violation.student.gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="card border-0 shadow mb-4">
          <div class="card-header">
            <h5 class="mb-0"><i class="fa fa-file-alt"></i> Informasi Ujian</h5>
          </div>
          <div class="card-body">
            <table class="table table-borderless">
              <tbody>
                <tr>
                  <td width="40%"><strong>Ujian</strong></td>
                  <td class="text-wrap">{{ violation.exam.title }}</td>
                </tr>
                <tr>
                  <td><strong>Mata Pelajaran</strong></td>
                  <td class="text-wrap">{{ violation.exam.lesson?.title }}</td>
                </tr>
                <tr>
                  <td><strong>Sesi</strong></td>
                  <td class="text-wrap">{{ violation.exam_session.title }}</td>
                </tr>
                <tr>
                  <td><strong>Durasi Ujian</strong></td>
                  <td>{{ violation.exam.duration }} menit</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card border-0 shadow mb-4">
          <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="fa fa-exclamation-triangle"></i> Detail Pelanggaran</h5>
          </div>
          <div class="card-body">
            <table class="table table-borderless">
              <tbody>
                <tr>
                  <td width="40%"><strong>Tipe Pelanggaran</strong></td>
                  <td>
                    <span class="badge bg-danger">{{ getViolationTypeLabel(violation.violation_type) }}</span>
                  </td>
                </tr>
                <tr>
                  <td><strong>Jumlah Pelanggaran</strong></td>
                  <td>
                    <span :class="getBadgeClass(violation.violation_count)">
                      {{ violation.violation_count }}x pelanggaran
                    </span>
                  </td>
                </tr>
                <tr>
                  <td><strong>Waktu Terakhir</strong></td>
                  <td>{{ formatDate(violation.violated_at) }}</td>
                </tr>
                <tr>
                  <td><strong>Status</strong></td>
                  <td>
                    <span :class="getStatusClass(violation.status)">
                      {{ getStatusLabel(violation.status) }}
                    </span>
                  </td>
                </tr>
                <tr v-if="violation.description">
                  <td><strong>Keterangan</strong></td>
                  <td class="text-wrap">{{ violation.description }}</td>
                </tr>
              </tbody>
            </table>

            <div v-if="violation.status === 'forgiven'" class="alert alert-success mt-3">
              <strong><i class="fa fa-check-circle"></i> Dimaafkan oleh:</strong><br />
              {{ violation.forgiven_by_user?.name }}<br />
              <small>{{ formatDate(violation.forgiven_at) }}</small
              ><br />
              <strong>Alasan:</strong> {{ violation.forgiven_reason }}
            </div>
          </div>
        </div>

        <div class="card border-0 shadow mb-4" v-if="grade">
          <div class="card-header">
            <h5 class="mb-0"><i class="fa fa-chart-bar"></i> Progress Ujian</h5>
          </div>
          <div class="card-body">
            <table class="table table-borderless">
              <tbody>
                <tr>
                  <td width="40%"><strong>Waktu Mulai</strong></td>
                  <td>{{ grade.start_time ? formatDate(grade.start_time) : '-' }}</td>
                </tr>
                <tr>
                  <td><strong>Waktu Selesai</strong></td>
                  <td>{{ grade.end_time ? formatDate(grade.end_time) : 'Belum selesai' }}</td>
                </tr>
                <tr>
                  <td><strong>Durasi Tersisa</strong></td>
                  <td>{{ formatDuration(grade.duration) }}</td>
                </tr>
                <tr>
                  <td><strong>Jawaban Benar</strong></td>
                  <td>{{ grade.total_correct }}</td>
                </tr>
                <tr>
                  <td><strong>Nilai</strong></td>
                  <td>
                    <span class="badge bg-primary fs-5">{{ grade.grade }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="card border-0 shadow mb-4" v-if="violation.status === 'active'">
          <div class="card-body">
            <h5><i class="fa fa-redo"></i> Reset Pelanggaran</h5>
            <hr />
            <p class="text-muted">
              Siswa masih dalam ujian. Anda dapat mereset hitungan pelanggaran agar siswa bisa melanjutkan tanpa
              khawatir.
            </p>
            <div class="mb-3">
              <label class="form-label">Alasan Reset:</label>
              <textarea
                v-model="forgiveForm.reason"
                class="form-control"
                rows="3"
                placeholder="Contoh: Siswa mengalami masalah teknis..."
              ></textarea>
              <div v-if="errors.reason" class="text-danger mt-1">{{ errors.reason }}</div>
            </div>
            <button type="button" class="btn btn-warning w-100" @click="forgiveViolation">
              <i class="fa fa-redo"></i> Reset Pelanggaran
            </button>
          </div>
        </div>

        <div class="card border-0 shadow" v-if="violation.status === 'ended'">
          <div class="card-body">
            <h5><i class="fa fa-unlock"></i> Izinkan Melanjutkan Ujian</h5>
            <hr />
            <p class="text-muted">
              Dengan mengizinkan, siswa dapat melanjutkan ujian dengan waktu dan jawaban yang tersisa.
            </p>
            <div class="mb-3">
              <label class="form-label">Alasan Mengizinkan:</label>
              <textarea
                v-model="forgiveForm.reason"
                class="form-control"
                rows="3"
                placeholder="Contoh: Siswa mengalami masalah teknis..."
              ></textarea>
              <div v-if="errors.reason" class="text-danger mt-1">{{ errors.reason }}</div>
            </div>
            <button type="button" class="btn btn-success w-100" @click="forgiveViolation">
              <i class="fa fa-check"></i> Izinkan Lanjut Ujian
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import LayoutAdmin from '../../../Layouts/Admin.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import Swal from 'sweetalert2';

export default {
  layout: LayoutAdmin,

  components: {
    Head,
    Link,
  },

  props: {
    violation: Object,
    grade: Object,
    errors: Object,
  },

  setup(props) {
    const forgiveForm = reactive({
      reason: '',
    });

    const formatDate = (date) => {
      if (!date) return '-';
      return new Date(date).toLocaleString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
      });
    };

    const formatDuration = (ms) => {
      if (!ms) return '-';
      const minutes = Math.floor(ms / 60000);
      const seconds = Math.floor((ms % 60000) / 1000);
      return `${minutes} menit ${seconds} detik`;
    };

    const getBadgeClass = (count) => {
      if (count >= 3) return 'badge bg-danger';
      if (count >= 2) return 'badge bg-warning';
      return 'badge bg-info';
    };

    const getStatusClass = (status) => {
      const classes = {
        active: 'badge bg-warning',
        forgiven: 'badge bg-success',
        ended: 'badge bg-danger',
      };
      return classes[status] || 'badge bg-secondary';
    };

    const getStatusLabel = (status) => {
      const labels = {
        active: 'Aktif',
        forgiven: 'Dimaafkan',
        ended: 'Ujian Diakhiri',
      };
      return labels[status] || status;
    };

    const getViolationTypeLabel = (type) => {
      const labels = {
        tab_switch: 'Berpindah Tab',
        fullscreen_exit: 'Keluar Fullscreen',
        blur: 'Window Blur',
      };
      return labels[type] || type;
    };

    const forgiveViolation = () => {
      router.post(`/admin/exam-violations/${props.violation.id}/forgive`, forgiveForm, {
        onSuccess: () => {
          Swal.fire({
            title: 'Berhasil!',
            text: 'Siswa telah diizinkan melanjutkan ujian.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false,
          });
        },
      });
    };

    return {
      forgiveForm,
      formatDate,
      formatDuration,
      getBadgeClass,
      getStatusClass,
      getStatusLabel,
      getViolationTypeLabel,
      forgiveViolation,
    };
  },
};
</script>

<style scoped>
.table-borderless {
  table-layout: fixed;
  width: 100%;
}

.table-borderless td {
  vertical-align: top;
}

.table-borderless td:first-child {
  white-space: nowrap;
  width: 40%;
}

.text-wrap {
  word-wrap: break-word;
  word-break: break-word;
  white-space: normal !important;
}
</style>
