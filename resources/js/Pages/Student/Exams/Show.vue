<template>
  <div class="wrapper">
    <Head>
      <title>Ujian Dengan Nomor Soal : {{ currentPage }} - Aplikasi Ujian Online</title>
    </Head>

    <!-- Overlay untuk request fullscreen -->
    <div v-if="showFullscreenPrompt" class="fullscreen-overlay">
      <div class="fullscreen-modal">
        <h3><i class="fa fa-expand"></i> Mode Fullscreen Diperlukan</h3>
        <p>Ujian harus dilakukan dalam mode fullscreen untuk mencegah kecurangan.</p>
        <button @click="enterFullscreenAndStart" class="btn btn-lg btn-success">
          <i class="fa fa-play"></i> Mulai Ujian dalam Fullscreen
        </button>
      </div>
    </div>

    <div class="row mb-5" v-show="!showFullscreenPrompt">
      <div class="col-md-7">
        <div class="card border-0 shadow">
          <div class="card-header">
            <div class="d-flex justify-content-between">
              <div>
                <h5 class="mb-0">
                  Soal No. <strong class="fw-bold">{{ currentPage }}</strong>
                </h5>
              </div>
              <div>
                <VueCountdown
                  :time="duration"
                  @progress="handleChangeDuration"
                  @end="showModalEndTimeExam = true"
                  v-slot="{ hours, minutes, seconds }"
                >
                  <span class="badge bg-info p-2">
                    <i class="fa fa-clock"></i> {{ hours }} jam, {{ minutes }} menit, {{ seconds }} detik.</span
                  >
                </VueCountdown>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div v-if="question_active !== null">
              <div>
                <p v-html="question_active.question.question"></p>
              </div>

              <table>
                <tbody>
                  <tr v-for="(answer, index) in answer_order" :key="index">
                    <td width="50" style="padding: 10px">
                      <button
                        :class="{
                          'btn btn-info btn-sm w-100 shdaow': Number(answer) === Number(question_active.answer),
                          'btn btn-outline-info btn-sm w-100 shdaow': Number(answer) !== Number(question_active.answer),
                        }"
                        @click.prevent="selectAnswer(question_active.question.id, answer)"
                      >
                        {{ options[index] }}
                      </button>
                    </td>
                    <td style="padding: 10px">
                      <p v-html="question_active.question['option_' + answer]"></p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div v-else>
              <div class="alert alert-danger border-0 shadow">
                <i class="fa fa-exclamation-triangle"></i> Soal Tidak Ditemukan!.
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="d-flex justify-content-between" v-if="!isFullscreen">
              <div class="text-start">
                <button
                  v-if="currentPage > 1"
                  @click.prevent="prevPage"
                  type="button"
                  class="btn btn-gray-400 btn-sm btn-block mb-2"
                >
                  Sebelumnya
                </button>
              </div>
              <div class="text-end">
                <button
                  v-if="currentPage < all_questions.length"
                  @click.prevent="nextPage"
                  type="button"
                  class="btn btn-gray-400 btn-sm"
                >
                  Selanjutnya
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-5" v-if="!isFullscreen">
        <div class="card border-0 shadow">
          <div class="card-header text-center">
            <div class="badge bg-success p-2">{{ question_answered }} dikerjakan</div>
          </div>
          <div class="card-body" style="height: 330px; overflow-y: auto">
            <div v-for="(question, index) in all_questions" :key="index">
              <div width="20%" style="width: 20%; float: left">
                <div style="padding: 5px">
                  <button
                    @click.prevent="clickQuestion(index)"
                    v-if="index + 1 == currentPage"
                    class="btn btn-gray-400 btn-sm w-100"
                  >
                    {{ index + 1 }}
                  </button>

                  <button
                    @click.prevent="clickQuestion(index)"
                    v-if="index + 1 != currentPage && question.answer == 0"
                    class="btn btn-outline-info btn-sm w-100"
                  >
                    {{ index + 1 }}
                  </button>

                  <button
                    @click.prevent="clickQuestion(index)"
                    v-if="index + 1 != currentPage && question.answer != 0"
                    class="btn btn-info btn-sm w-100"
                  >
                    {{ index + 1 }}
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button @click="showModalEndExam = true" class="btn btn-danger btn-md border-0 shadow w-100">
              Akhiri Ujian
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- modal akhiri ujian -->
    <div
      v-if="showModalEndExam"
      class="modal fade"
      :class="{ show: showModalEndExam }"
      tabindex="-1"
      aria-hidden="true"
      style="display: block"
      role="dialog"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Akhiri Ujian ?</h5>
          </div>
          <div class="modal-body">
            Setelah mengakhiri ujian, Anda tidak dapat kembali ke ujian ini lagi. Yakin akan mengakhiri ujian?
          </div>
          <div class="modal-footer">
            <button @click.prevent="endExam" type="button" class="btn btn-primary">Ya, Akhiri</button>
            <button @click.prevent="showModalEndExam = false" type="button" class="btn btn-secondary">Tutup</button>
          </div>
        </div>
      </div>
    </div>

    <!-- modal waktu ujian berakhir -->
    <div
      v-if="showModalEndTimeExam"
      class="modal fade"
      :class="{ show: showModalEndTimeExam }"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-hidden="true"
      style="display: block"
      role="dialog"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Waktu Habis !</h5>
          </div>
          <div class="modal-body">
            Waktu ujian sudah berakhir!. Klik <strong class="fw-bold">Ya</strong> untuk mengakhiri ujian.
          </div>
          <div class="modal-footer">
            <button @click.prevent="endExam" type="button" class="btn btn-primary">Ya</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
//import layout student
import LayoutStudent from '../../../Layouts/Student.vue';

//import Head and Link from Inertia
import { Head, Link, router } from '@inertiajs/vue3';

//import ref
import { ref, onMounted, nextTick, computed } from 'vue';

//import VueCountdown
import VueCountdown from '@chenfengyuan/vue-countdown';

//import axios
import axios from 'axios';

//import sweet alert2
import Swal from 'sweetalert2';

export default {
  //layout
  layout: LayoutStudent,

  //register components
  components: {
    Head,
    Link,
    VueCountdown,
  },

  inheritAttrs: false,

  //props
  props: {
    id: Number,
    page: Number,
    exam_group: Object,
    all_questions: Array,
    question_answered: Number,
    question_active: Object,
    answer_order: Array,
    duration: Object,
  },

  //composition API
  setup(props) {
    //define options for answer
    let options = ['A', 'B', 'C', 'D', 'E'];

    //define state counter
    const counter = ref(0);

    //define state duration
    const duration = ref(props.duration ? props.duration.duration : 3600000);

    //define current page reactive
    const currentPage = ref(props.page);

    //reactive all_questions
    const all_questions = ref([...props.all_questions]);

    //computed question_active based on currentPage
    const question_active = computed(() => {
      return all_questions.value[currentPage.value - 1] || null;
    });

    //computed isFullscreen
    const isFullscreen = computed(() => {
      return !!document.fullscreenElement;
    });

    //define state untuk fullscreen prompt
    const showFullscreenPrompt = ref(true);

    //define state untuk flag apakah sedang submit
    const isSubmitting = ref(false);

    // Fullscreen logic
    const forceFullscreen = () => {
      try {
        const el = document.documentElement;
        if (!document.fullscreenElement) {
          if (el.requestFullscreen) {
            el.requestFullscreen().catch(() => {});
          } else if (el.webkitRequestFullscreen) {
            el.webkitRequestFullscreen();
          } else if (el.msRequestFullscreen) {
            el.msRequestFullscreen();
          }
        }
      } catch (error) {
        console.warn('Fullscreen request failed:', error);
      }
    };

    // Listen keluar fullscreen
    const handleFullscreenChange = () => {
      if (!document.fullscreenElement) {
        Swal.fire({
          title: 'Mode Fullscreen Diperlukan',
          text: 'Anda harus tetap dalam mode fullscreen selama ujian berlangsung.',
          icon: 'warning',
          confirmButtonText: 'Kembali ke Fullscreen',
          allowOutsideClick: false,
          allowEscapeKey: false,
        }).then(() => {
          setTimeout(() => {
            forceFullscreen();
          }, 100);
        });
      }
    };

    // Listen tab switch
    const handleVisibilityChange = () => {
      if (document.visibilityState !== 'visible') {
        Swal.fire({
          title: 'Jangan Berpindah Tab!',
          text: 'Anda harus tetap berada di halaman ujian sampai selesai.',
          icon: 'error',
          confirmButtonText: 'Kembali',
          allowOutsideClick: false,
          allowEscapeKey: false,
        });
      }
    };

    // Blokir navigasi selama ujian
    const blockNavigation = (event) => {
      //jangan tampilkan warning saat submit/posting data
      if (isSubmitting.value) {
        return;
      }
      event.preventDefault();
      event.returnValue = '';
    };

    // Function untuk enter fullscreen dengan user gesture
    const enterFullscreenAndStart = async () => {
      try {
        const el = document.documentElement;
        if (el.requestFullscreen) {
          await el.requestFullscreen();
        } else if (el.webkitRequestFullscreen) {
          el.webkitRequestFullscreen();
        } else if (el.msRequestFullscreen) {
          el.msRequestFullscreen();
        }
        showFullscreenPrompt.value = false;
      } catch (error) {
        console.warn('Fullscreen failed:', error);
        showFullscreenPrompt.value = false;
      }
    };

    // Inisialisasi pada mount
    onMounted(() => {
      // Block browser back button
      history.pushState(null, '', location.href);
      window.addEventListener('popstate', function () {
        history.pushState(null, '', location.href);
        Swal.fire({
          title: 'Tidak Dapat Kembali!',
          text: 'Anda tidak diperbolehkan meninggalkan halaman ujian.',
          icon: 'warning',
          confirmButtonText: 'OK',
          allowOutsideClick: false,
          allowEscapeKey: false,
        });
      });

      document.addEventListener('fullscreenchange', handleFullscreenChange);
      document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
      document.addEventListener('msfullscreenchange', handleFullscreenChange);
      document.addEventListener('visibilitychange', handleVisibilityChange);
      window.addEventListener('beforeunload', blockNavigation);
    });

    // Bersihkan event listener saat selesai ujian
    const cleanup = () => {
      document.removeEventListener('fullscreenchange', handleFullscreenChange);
      document.removeEventListener('webkitfullscreenchange', handleFullscreenChange);
      document.removeEventListener('msfullscreenchange', handleFullscreenChange);
      document.removeEventListener('visibilitychange', handleVisibilityChange);
      window.removeEventListener('beforeunload', blockNavigation);
      if (document.exitFullscreen) {
        document.exitFullscreen();
      } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
      } else if (document.msExitFullscreen) {
        document.msExitFullscreen();
      }
    };

    //handleChangeDuration
    const handleChangeDuration = () => {
      //decrement duration
      duration.value = duration.value - 1000;

      //increment counter
      counter.value = counter.value + 1;

      //cek jika durasi di atas 0
      if (duration.value > 0) {
        //update duration if 10 seconds
        if (counter.value % 10 == 1) {
          //update duration
          axios.put(`/student/exam-duration/update/${props.duration.id}`, {
            duration: duration.value,
          });
        }
      }
    };

    //metohd prevPage
    const prevPage = () => {
      //update duration
      axios.put(`/student/exam-duration/update/${props.duration.id}`, {
        duration: duration.value,
      });

      //update current page
      currentPage.value--;
    };

    //method nextPage
    const nextPage = () => {
      //update duration
      axios.put(`/student/exam-duration/update/${props.duration.id}`, {
        duration: duration.value,
      });

      //update current page
      currentPage.value++;
    };

    //method clickQuestion
    const clickQuestion = (index) => {
      //update duration
      axios.put(`/student/exam-duration/update/${props.duration.id}`, {
        duration: duration.value,
      });

      //update current page
      currentPage.value = index + 1;
    };

    //method select answer with optimistic update
    const selectAnswer = (question_id, answer) => {
      //set submitting flag
      isSubmitting.value = true;

      //optimistic update - update UI immediately
      const index = all_questions.value.findIndex((q) => q.question.id === question_id);
      if (index !== -1) {
        all_questions.value[index].answer = answer;
      }

      //submit to server asynchronously without waiting
      router.post(
        '/student/exam-answer',
        {
          exam_id: props.exam_group.exam.id,
          exam_session_id: props.exam_group.exam_session.id,
          question_id: question_id,
          answer: answer,
          duration: duration.value,
        },
        {
          onFinish: () => {
            //reset submitting flag after response
            isSubmitting.value = false;
          },
        }
      );
    };

    //define state modal
    const showModalEndExam = ref(false);
    const showModalEndTimeExam = ref(false);

    //method endExam
    const endExam = () => {
      cleanup();
      router.post('/student/exam-end', {
        exam_group_id: props.exam_group.id,
        exam_id: props.exam_group.exam.id,
        exam_session_id: props.exam_group.exam_session.id,
      });

      //show success alert
      Swal.fire({
        title: 'Success!',
        text: 'Ujian Selesai!.',
        icon: 'success',
        showConfirmButton: false,
        timer: 4000,
      });
    };

    //return
    return {
      options,
      duration,
      handleChangeDuration,
      prevPage,
      nextPage,
      clickQuestion,
      selectAnswer,
      showModalEndExam,
      showModalEndTimeExam,
      endExam,
      showFullscreenPrompt,
      enterFullscreenAndStart,
      currentPage,
      all_questions,
      question_active,
      isFullscreen,
      isSubmitting,
    };
  },
};
</script>

<style>
.fullscreen-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.9);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.fullscreen-modal {
  background: white;
  padding: 40px;
  border-radius: 10px;
  text-align: center;
  max-width: 500px;
}

.fullscreen-modal h3 {
  margin-bottom: 20px;
  color: #333;
}

.fullscreen-modal p {
  margin-bottom: 30px;
  color: #666;
}
</style>
