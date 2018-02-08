<template>
  <gmap-map
    :zoom="zoom"
    :center="center"
    style="width: 100%; height: 400px"
  >
    <gmap-info-window
      :options="infoOptions"
      :position="infoPosition"
      :opened="infoWinOpen"
      @closeclick="infoWinOpen=false"
    >
      <b><a v-bind:href="href">{{name}}</a></b>
      <br />{{date}}
    </gmap-info-window>

    <gmap-circle
      :key="index"
      v-for="(m, index) in markers"
      :center="m.position"
      :radius="m.radius"
      :options="m.options"
    ></gmap-circle>

    <gmap-marker
      :key="index"
      v-for="(m, index) in markers"
      :position="m.position"
      :clickable="true"
      :draggable="false"
      @click="toggleInfo(m,index)"
    ></gmap-marker>
  </gmap-map>
</template>
 
<script>
  import * as VueGoogleMaps from 'vue2-google-maps';
  import Vue from 'vue';
 
  Vue.use(VueGoogleMaps, {
    load: {
      key: 'AIzaSyCrHQQfixq1IVYwzBrK8y20vz60D0I5c3Y',
    }
  });
 
  export default {

    props: ['lat', 'lng', 'zoom', 'group'],

    data () {
      return {
        center: {lat: this.lat, lng: this.lng},
        markers: null,
        name: '',
        date: '',
        href: '',
        infoPosition: {
          lat: 0,
          lng: 0
        },
        infoWinOpen: false,
        currentMidx: null,
        infoOptions: {
          pixelOffset: {
            width: 0,
            height: -35
          }
        },
      }
    },

    created: function () {
      this.fetchData();
    },

    methods: {
      fetchData: function () {
        var api = this.group ? '/api/map/' + this.group : '/api/map';
        var self = this;
        $.get(api, function( data ) {
          self.markers = data;
          console.log(data);
        });
      },
      toggleInfo: function(marker, idx) {
        this.infoPosition = marker.position;
        this.name = marker.name;
        this.date = marker.date;
        this.href = marker.href;
        if (this.currentMidx == idx) {
            this.infoWinOpen = !this.infoWinOpen;
        }
        else {
          this.infoWinOpen = true;
          this.currentMidx = idx;
        }
      }
    },

  }
</script>