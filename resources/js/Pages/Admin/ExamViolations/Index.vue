<template>
  <Head>
    <title>Pelanggaran Ujian - Aplikasi Ujian Online</title>
  </Head>
  <div class="container-fluid mb-5 mt-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card border-0 shadow">
          <div class="card-header">
            <div class="row">
              <div class="col-md-6">
                <h5 class="mt-2"><i class="fa fa-exclamation-triangle"></i> Pelanggaran Ujian</h5>
              </div>
              <div class="col-md-6">
                <form @submit.prevent="search">
                  <div class="input-group">
                    <select v-model="searchForm.status" class="form-select" style="max-width: 150px">
                      <option value="">Semua Status</option>
                      <option value="active">Aktif</option>
                      <option value="forgiven">Dimaafkan</option>
                      <option value="ended">Diakhiri</option>
                    </select>
                    <input
                      type="text"
                      class="form-control"
                      v-model="searchForm.search"
                      placeholder="Cari nama/NISN siswa..."
                    />
                    <button class="btn btn-primary" type="submit">
                      <i class="fa fa-search"></i>
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                <thead class="thead-dark">
                  <tr>
                    <th style="width: 5%">No.</th>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Ujian</th>
                    <th>Sesi</th>
                    <th>Jumlah Pelanggaran</th>
                    <th>Waktu</th>
                    <th>Status</th>
                    <th style="width: 15%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="violations.data.length === 0">
                    <td colspan="9" class="text-center">Tidak ada data pelanggaran</td>
                  </tr>
                  <tr v-for="(violation, index) in violations.data" :key="violation.id">
                    <td class="text-center">{{ violations.from + index }}</td>
                    <td>
                      <strong>{{ violation.student.name }}</strong>
                      <br />
                      <small class="text-muted">{{ violation.student.nisn }}</small>
                    </td>
                    <td>{{ violation.student.classroom?.title }}</td>
                    <td>{{ violation.exam.title }}</td>
                    <td>{{ violation.exam_session.title }}</td>
                    <td class="text-center">
                      <span :class="getBadgeClass(violation.violation_count)"> {{ violation.violation_count }}x </span>
                    </td>
                    <td>{{ formatDate(violation.violated_at) }}</td>
                    <td>
                      <span :class="getStatusClass(violation.status)">
                        {{ getStatusLabel(violation.status) }}
                      </span>
                    </td>
                    <td>
                      <Link :href="`/admin/exam-violations/${violation.id}`" class="btn btn-sm btn-info me-1">
                        <i class="fa fa-eye"></i>
                      </Link>
                      <button
                        v-if="violation.status === 'ended'"
                        @click="openForgiveModal(violation)"
                        class="btn btn-sm btn-success me-1"
                      >
                        <i class="fa fa-check"></i> Izinkan
                      </button>
                      <button @click="deleteViolation(violation.id)" class="btn btn-sm btn-danger">
                        <i class="fa fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="d-flex justify-content-between mt-3" v-if="violations.last_page > 1">
              <div>Menampilkan {{ violations.from }} - {{ violations.to }} dari {{ violations.total }} data</div>
              <nav>
                <ul class="pagination mb-0">
                  <li
                    v-for="link in violations.links"
                    :key="link.label"
                    :class="['page-item', { active: link.active, disabled: !link.url }]"
                  >
                    <Link v-if="link.url" :href="link.url" class="page-link" v-html="link.label"></Link>
                    <span v-else class="page-link" v-html="link.label"></span>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Forgive Modal -->
  <div v-if="showForgiveModal" class="modal fade show" style="display: block" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Izinkan Siswa Melanjutkan Ujian</h5>
          <button type="button" class="btn-close" @click="showForgiveModal = false"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info">
            <strong>Siswa:</strong> {{ selectedViolation?.student?.name }}<br />
            <strong>Ujian:</strong> {{ selectedViolation?.exam?.title }}
          </div>
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
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="showForgiveModal = false">Batal</button>
          <button type="button" class="btn btn-success" @click="forgiveViolation">
            <i class="fa fa-check"></i> Izinkan Lanjut Ujian
          </button>
        </div>
      </div>
    </div>
  </div>
  <div v-if="showForgiveModal" class="modal-backdrop fade show"></div>
</template>

<script>
import LayoutAdmin from '../../../Layouts/Admin.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';
import Swal from 'sweetalert2';

export default {
  layout: LayoutAdmin,

  components: {
    Head,
    Link,
  },

  props: {
    violations: Object,
    filters: Object,
    errors: Object,
  },

  setup(props) {
    const searchForm = reactive({
      status: props.filters?.status || '',
      search: props.filters?.search || '',
    });

    const showForgiveModal = ref(false);
    const selectedViolation = ref(null);
    const forgiveForm = reactive({
      reason: '',
    });

    const search = () => {
      router.get('/admin/exam-violations', searchForm, {
        preserveState: true,
      });
    };

    const formatDate = (date) => {
      return new Date(date).toLocaleString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
      });
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
        ended: 'Diakhiri',
      };
      return labels[status] || status;
    };

    const openForgiveModal = (violation) => {
      selectedViolation.value = violation;
      forgiveForm.reason = '';
      showForgiveModal.value = true;
    };

    const forgiveViolation = () => {
      router.post(`/admin/exam-violations/${selectedViolation.value.id}/forgive`, forgiveForm, {
        onSuccess: () => {
          showForgiveModal.value = false;
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

    const deleteViolation = (id) => {
      Swal.fire({
        title: 'Hapus Pelanggaran?',
        text: 'Data pelanggaran akan dihapus permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
      }).then((result) => {
        if (result.isConfirmed) {
          router.delete(`/admin/exam-violations/${id}`);
        }
      });
    };

    return {
      searchForm,
      search,
      formatDate,
      getBadgeClass,
      getStatusClass,
      getStatusLabel,
      showForgiveModal,
      selectedViolation,
      forgiveForm,
      openForgiveModal,
      forgiveViolation,
      deleteViolation,
    };
  },
};
</script>

<style scoped>
.modal-backdrop {
  background-color: rgba(0, 0, 0, 0.5);
}
</style>
