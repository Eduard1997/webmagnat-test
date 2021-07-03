<template>
<div class="container-fluid">
  <b-card no-body class="overflow-hidden fadeIn" style="max-width: 700px; margin: 100px auto 0;">
    <b-row no-gutters>
      <b-col md="6">
        <b-card-img src="https://picsum.photos/400/400/?image=20" alt="Image" class="rounded-0"></b-card-img>
      </b-col>
      <b-col md="6">
        <b-card-body :title="cardTitle">
          <pulse-loader class="centered" :loading="loading" :color="color"></pulse-loader>
          <b-form-group v-slot="{ ariaDescribedby }" style="text-align: left" v-if="displayForm">
            <template v-for="(answer, key) in pollOptions" >
              <b-form-radio :key="key" v-model="selectedAnswer" :aria-describedby="ariaDescribedby" name="some-radios" :value="answer.name">{{answer.name}}</b-form-radio>
            </template>
          </b-form-group>

          <b-list-group v-if="showPercents">
            <b-list-group-item class="d-flex justify-content-between align-items-center">
              Good
              <b-badge variant="primary" pill>{{goodPercent}}%</b-badge>
              <b-badge variant="primary" pill>{{goodAnswerCount}} results</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex justify-content-between align-items-center">
              Fair
              <b-badge variant="primary" pill>{{fairPercent}}%</b-badge>
              <b-badge variant="primary" pill>{{fairAnswerCount}} results</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex justify-content-between align-items-center">
              Neutral
              <b-badge variant="primary" pill>{{neutralPercent}}%</b-badge>
              <b-badge variant="primary" pill>{{neutralAnswerCount}} results</b-badge>
            </b-list-group-item>
            <b-list-group-item class="d-flex justify-content-between align-items-center">
              Bad
              <b-badge variant="primary" pill>{{badPercent}}%</b-badge>
              <b-badge variant="primary" pill>{{badAnswerCount}} results</b-badge>
            </b-list-group-item>
          </b-list-group>

          <b-button class="submit-button" variant="success" disabled @click="submitResponse" v-if="showSubmit">Submit</b-button>
          <b-button class="submit-button mt-2" variant="success" @click="resetFunctionality" v-if="voteAgain">Vote again</b-button>
        </b-card-body>
      </b-col>
    </b-row>
  </b-card>
</div>
</template>

<script>
import axios from "axios";
import PulseLoader from 'vue-spinner/src/PulseLoader.vue';

export default {
  components: {
    PulseLoader
  },
name: "LandingPage",
  data() {
    return {
      pollOptions: '',
      selectedAnswer: '',
      loading: true,
      color: 'green',
      displayForm: false,
      showPercents: false,
      goodPercent: '',
      badPercent: '',
      fairPercent: '',
      neutralPercent: '',
      showSubmit: true,
      cardTitle: 'How do you find our service?',
      voteAgain: false,
      goodAnswerCount: '',
      badAnswerCount: '',
      fairAnswerCount: '',
      neutralAnswerCount: '',
    }
  },
  methods: {
    getPollOptions() {
      axios.get('http://localhost:80/get-poll-options').then((response) => {
        this.pollOptions = response.data.poll_options;
        this.loading = false;
        this.displayForm = true;
      })
    },
    submitResponse() {
      this.loading = true;
      this.displayForm = false;
      this.showSubmit = false;
      axios.get('http://localhost:80/save-answer?answer=' + this.selectedAnswer).then((response) => {
        if(response.data.success !== 'undefined') {
          this.goodPercent = response.data.percents.goodAnswerPercent;
          this.badPercent = response.data.percents.badAnswersPercent;
          this.fairPercent = response.data.percents.fairAnswersPercent;
          this.neutralPercent = response.data.percents.neutralAnswersPercent;
          this.goodAnswerCount = response.data.percents.goodAnswerCount;
          this.badAnswerCount = response.data.percents.badAnswerCount;
          this.fairAnswerCount = response.data.percents.fairAnswerCount;
          this.neutralAnswerCount = response.data.percents.neutralAnswerCount;
          this.loading = false;
          this.showPercents = true;
          this.voteAgain = true;
          this.cardTitle = 'Thank you! Do you want to vote again?'
        }
      });
    },
    resetFunctionality() {
      this.displayForm = true;
      this.showPercents = false;
      this.showSubmit = true;
      this.cardTitle = 'Thank you! Do you want to vote again?';
      this.selectedAnswer = '';
      this.voteAgain = false;
    }
  },
  created() {
    this.getPollOptions();
  },
  watch: {
    'selectedAnswer': function(newVal) {
      if(newVal != null)
        document.querySelector('.submit-button').removeAttribute('disabled');
        document.querySelector('.submit-button').classList.remove('disabled');
    }
}

}
</script>

<style scoped>
.fadeIn {
  -moz-animation: fadein 5s; /* Firefox */
  -webkit-animation: fadein 5s; /* Safari and Chrome */
  -o-animation: fadein 5s; /* Opera */
}
@keyframes fadein {
  from {
    opacity:0;
  }
  to {
    opacity:1;
  }
}
@-moz-keyframes fadein { /* Firefox */
  from {
    opacity:0;
  }
  to {
    opacity:1;
  }
}
@-webkit-keyframes fadein { /* Safari and Chrome */
  from {
    opacity:0;
  }
  to {
    opacity:1;
  }
}
@-o-keyframes fadein { /* Opera */
  from {
    opacity:0;
  }
  to {
    opacity: 1;
  }
}
</style>