<template>
    <div class="card mb-4">
        <div class="card-header">
            <div class="float-right"><span class="memory-label">Turns:</span> <span class="memory-value">{{ turns }} </span></div>
            <div><span class="memory-label">Time:</span> <span class="memory-value">{{ time }} </span></div>
        </div>
        <div class="card-body">
            <div class="memory-cards">
                <div class="memory-card" v-for="card in cards" :class="{ flipped: card.flipped, found: card.found }" @click="flipCard(card)">
                    <div class="memory-back"></div>
                    <div class="memory-front" :style="{ backgroundImage: 'url(' + card.card + ')' }"></div>
                </div>
            </div>
            <div class="memory-splash" v-if="showSplash">
                <div class="memory-overlay"></div>
                <div class="memory-content">
                    <div class="memory-title">You won!</div>
                    <div class="memory-score">Score: {{ score }}</div>
                    <button class="memory-newGame" @click="fetchData()">New game</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

  import VueMoment from 'vue-moment';
  import moment from 'moment-timezone';

  Vue.use(VueMoment, {
    moment,
  });

  export default {

    data () {
      return {
        showSplash: false,
        cardTypes: [],
        cards: [],
        started: false,
        startTime: 0,
        turns: 0,
        flipBackTimer: null,
        timer: null,
        time: "--:--",
        score: 0
      }
    },

    created: function () {
      this.fetchData();
    },

    methods: {
      fetchData() {
        var api = '/api/arcade/memory-game';
        var self = this;
        $.get(api, function( data ) {
          self.cardTypes = data;
          self.resetGame();
        });
      },

      shuffleCards() {
        let cards = [].concat(_.cloneDeep(this.cardTypes), _.cloneDeep(this.cardTypes));
        return _.shuffle(cards);
      },

      resetGame() {
        this.showSplash = false;
        let cards = this.shuffleCards();
        this.turns = 0;
        this.score = 0;
        this.started = false;
        this.startTime = 0;
      
        _.each(cards, (card) => {
          card.flipped = false;
          card.found = false;
        });
      
        this.cards = cards;
      },
    
      flippedCards() {
        return _.filter(this.cards, card => card.flipped);
      },
    
      sameFlippedCard() {
        let flippedCards = this.flippedCards();
        if (flippedCards.length == 2) {
          if (flippedCards[0].name == flippedCards[1].name)
            return true;
        }
      },
    
      setCardFounds() {
        _.each(this.cards, (card) => {
          if (card.flipped)
            card.found = true;
        });
      },
    
      checkAllFound() {
        let foundCards = _.filter(this.cards, card => card.found);
        if (foundCards.length == this.cards.length)
          return true;
      },
    
      startGame() {
        this.started = true;
        this.startTime = moment();
      
        this.timer = setInterval(() => {
          this.time = moment(moment().diff(this.startTime)).format("mm:ss");
        }, 1000);
      },
    
      finishGame() {
        this.started = false;
        clearInterval(this.timer);
        let score = 1000 - (moment().diff(this.startTime, 'seconds') - this.cardTypes.length * 5) * 3 - (this.turns - this.cardTypes.length) * 5;
        this.score = Math.max(score, 0);
        this.showSplash = true;
      },
    
      flipCard(card) {
        if (card.found || card.flipped) return;
      
        if (!this.started) {
          this.startGame();
        }
      
        let flipCount = this.flippedCards().length;
        if (flipCount == 0) {
          card.flipped = !card.flipped;
        }
        else if (flipCount == 1) {
          card.flipped = !card.flipped;
          this.turns += 1;

          if (this.sameFlippedCard()) {
            // Match!
            this.flipBackTimer = setTimeout( ()=> {
              this.clearFlipBackTimer();
              this.setCardFounds();
              this.clearFlips();

              if (this.checkAllFound()) {
                this.finishGame();
              }  

            }, 200);
          }
          else {
            // Wrong match
            this.flipBackTimer = setTimeout( ()=> {
              this.clearFlipBackTimer();
              this.clearFlips();
            }, 1000);
          }
        }
      },
    
      clearFlips() {
        _.map(this.cards, card => card.flipped = false);
      },
  
      clearFlipBackTimer() {
        clearTimeout(this.flipBackTimer);
        this.flipBackTimer = null;
      }
    },

  }
</script>

<style>
.memory-info {
  text-align: center;
  padding-bottom: 1em;
  border-bottom: 1px solid #555;
}
.memory-info > div {
  display: inline-block;
  width: 200px;
}
.memory-info > div .memory-label {
  margin-right: 5px;
}
.memory-info > div .memory-value {
  font-weight: bold;
}
.memory-cards .memory-card {
  position: relative;
  display: inline-block;
  width: 150px;
  height: 208px;
  margin: 0;
  -moz-transition: opacity 0.5s;
  -o-transition: opacity 0.5s;
  -webkit-transition: opacity 0.5s;
  transition: opacity 0.5s;
}
.memory-cards .memory-card .memory-front, .memory-cards .memory-card .memory-back {
  border-radius: 5px;
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  width: 100%;
  height: 100%;
  background-color: White;
  -moz-backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-transform: translateZ(0);
  -ms-transform: translateZ(0);
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  -moz-transition: -moz-transform 0.6s;
  -o-transition: -o-transform 0.6s;
  -webkit-transition: -webkit-transform 0.6s;
  transition: transform 0.6s;
  -moz-transform-style: preserve-3d;
  -webkit-transform-style: preserve-3d;
  transform-style: preserve-3d;
}
.memory-cards .memory-card .memory-back {
  background-image: url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/102308/card_backside.jpg");
  background-size: 90%;
  background-position: center;
  background-repeat: no-repeat;
  font-size: 12px;
}
.memory-cards .memory-card .memory-front {
  -moz-transform: rotateY(-180deg);
  -ms-transform: rotateY(-180deg);
  -webkit-transform: rotateY(-180deg);
  transform: rotateY(-180deg);
  background-size: 90%;
  background-repeat: no-repeat;
  background-position: center;
}
.memory-cards .memory-card.flipped .memory-back, .memory-cards .memory-card.found .memory-back {
  -moz-transform: rotateY(180deg);
  -ms-transform: rotateY(180deg);
  -webkit-transform: rotateY(180deg);
  transform: rotateY(180deg);
}
.memory-cards .memory-card.flipped .memory-front, .memory-cards .memory-card.found .memory-front {
  -moz-transform: rotateY(0deg);
  -ms-transform: rotateY(0deg);
  -webkit-transform: rotateY(0deg);
  transform: rotateY(0deg);
}
.memory-cards .memory-card.found {
  opacity: 0.3;
}

.memory-splash {
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
}
.memory-splash .memory-overlay {
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.6);
}
.memory-splash .memory-content {
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  width: 400px;
  height: 200px;
  margin: auto;
  text-align: center;
  background-color: #FFF;
  -moz-border-radius: 10px;
  -webkit-border-radius: 10px;
  border-radius: 10px;
  -moz-box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.8);
  -webkit-box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.8);
  box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.8);
  padding: 1em;
}
.memory-splash .memory-content .memory-title {
  font-size: 1.8em;
  padding: 0.5em;
}
.memory-splash .memory-content .memory-score {
  padding: 0.5em;
}
.memory-splash .memory-content button {
  margin-top: 1.0em;
  background-color: #444;
  padding: 5px 20px;
  -moz-border-radius: 4px;
  -webkit-border-radius: 4px;
  border-radius: 4px;
  border: 1px solid #555;
  color: White;
  font-size: 1.4em;
  margin-bottom: 1.0em;
}
</style>