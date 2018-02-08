<template>
  <gmap-map
    :center="center"
    :zoom="12"
    :map-type-id="mapTypeId"
    style="width: 100%; height: 400px"
  >
    <gmap-info-window :options="infoOptions" :position="infoWindowPos" :opened="infoWinOpen" @closeclick="infoWinOpen=false">
      <b>{{infoContent}}</b><br />
      {{infoSubContent}}
    </gmap-info-window>
    <gmap-circle :center="center" :radius="radius" :options="{editable: false, strokeColor:'#000000', fillColor:'#FFFFFF'}"></gmap-circle>
    <gmap-marker
      :key="index"
      v-for="(m, index) in markers"
      :position="m.position"
      :clickable="true"
      :draggable="false"
      @click="toggleInfoWindow(m,index)"
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
    props: ['established', 'location', 'latitude', 'longitude', 'radius'],

    data () {
      return {
        center: {lat: this.latitude, lng: this.longitude},
        mapTypeId: 'satellite',
        infoContent: '',
        infoSubContent: '',
        infoWindowPos: {
          lat: 0,
          lng: 0
        },
        infoWinOpen: false,
        currentMidx: null,
        //optional: offset infowindow so it visually sits nicely on top of our marker
        infoOptions: {
          pixelOffset: {
            width: 0,
            height: -35
          }
        },
        markers: [{
          position: {lat: this.latitude, lng: this.longitude},
          infoText: this.location,
          infoSubText: this.established,
        }],
      }
    },

    methods: {
      toggleInfoWindow: function(marker, idx) {
        this.infoWindowPos = marker.position;
        this.infoContent = marker.infoText;
        this.infoSubContent = marker.infoSubText;
        //check if its the same marker that was selected if yes toggle
        if (this.currentMidx == idx) {
            this.infoWinOpen = !this.infoWinOpen;
        }
        //if different marker set infowindow to open and reset current marker index
        else {
          this.infoWinOpen = true;
          this.currentMidx = idx;
        }
      }
    }

  }
</script>