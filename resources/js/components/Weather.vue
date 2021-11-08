<template>
        <section >
            <div class="py-5 h-100">
                <div class="row">
                    <div class="input-group rounded mb-3" >
                        <input v-model="city" @keyup.enter="getWeatherByCity"  type="search" class="form-control rounded" placeholder="Введите название города" aria-label="Search"
                               aria-describedby="search-addon"/>
                        <button type="button"
                                @click="getWeatherByCity"
                                class="btn btn-light">Найти</button>
                    </div>
                </div>
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class=" col-lg-6">
                        <div class="card" style="color: #4B515D; border-radius: 35px;">
                            <div class="card-body p-4">
                                <div v-if="!isLoading && !error">
                                    <div class="d-flex">
                                        <h6 class="flex-grow-1">{{ weatherName }}</h6>
                                    </div>

                                    <div class="d-flex flex-column text-center mt-5 mb-4">
                                        <h6 class="display-4 mb-0 font-weight-bold" style="color: #1C2331;">
                                            {{ temperature }}°C </h6>
                                        <span class="small" style="color: #868B94">{{ description }}</span>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1" style="font-size: 1rem;">
                                            <div><i class="bi bi-wind" style="color: #868B94;"></i> <span class="ms-1"> {{
                                                    windSpeed
                                                }} km/h </span></div>
                                            <div><i class="bi bi-moisture" style="color: #868B94;"></i> <span
                                                class="ms-1"> {{ humidity }}% </span></div>
                                            <div><i class="bi bi-cloud-fill" style="color: #868B94;"></i> <span
                                                class="ms-1"> {{ cloudiness }}% </span></div>
                                            <div><i class="bi bi-speedometer" style="color: #868B94;"></i> <span
                                                class="ms-1"> {{ pressure }} hPa </span></div>
                                        </div>
                                        <div>
                                            <img :src="getWeatherImage()" width="100px">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center" v-if="error">
                                    <h3>Город не найден.</h3>
                                </div>
                                <div v-if="isLoading" class="d-flex justify-content-center">
                                    <div class="lds-ellipsis">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
</template>

<script>
    import { mapState } from 'vuex';
    export default {
        data: function () {
            return {
                weatherName: '',
                temperature: '',
                description: '',
                windSpeed: '',
                humidity: '',
                cloudiness: '',
                pressure: '',
                icon: '',
                city:'',
                error: ''
            }
        },


        computed: {
            ...mapState(['isLoading', 'refCount'])
        },


        created() {
            axios.interceptors.request.use((config) => {
                this.$store.commit('loading', true);
                return config;
            }, (error) => {
                this.$store.commit('loading', false);
                return Promise.reject(error);
            });

            axios.interceptors.response.use((response) => {
                this.$store.commit('loading', false);
                return response;
            }, (error) => {
                this.$store.commit('loading', false);
                return Promise.reject(error);
            });
        },


        mounted() {
           this.getWeatherByCity();
        },


        methods:{
            getWeatherImage() {
                return "http://openweathermap.org/img/w/"+ this.icon +".png";
            },
            setWeatherData(data){

                if(data.error){
                    return this.error = data.error;
                }
                this.weatherName =  data.name;
                this.temperature = data.main.temp;
                this.description = this.capitalize(data.weather.description);
                this.windSpeed = data.wind.speed;
                this.humidity = data.main.humidity;
                this.cloudiness = data.clouds.all;
                this.pressure = data.main.pressure;
                this.icon = data.weather.icon;
                this.error = '';
            },
            getWeatherByCity(){//todo Сделать валидацию.
                axios.get('/cities/'+ this.city).then(response => this.setWeatherData(response.data.data));
            },
            capitalize(string){
                return string && string[0].toUpperCase() + string.slice(1);
            }
        }
    }
</script>

<style>


/** https://loading.io/css/ */
.lds-ellipsis {
    display: inline-block;
    position: relative;
    width: 64px;
    height: 64px;
}
.lds-ellipsis div {
    position: absolute;
    top: 27px;
    width: 11px;
    height: 11px;
    border-radius: 50%;
    background: #ddd;
    animation-timing-function: cubic-bezier(0, 1, 1, 0);
}
.lds-ellipsis div:nth-child(1) {
    left: 6px;
    animation: lds-ellipsis1 0.6s infinite;
}
.lds-ellipsis div:nth-child(2) {
    left: 6px;
    animation: lds-ellipsis2 0.6s infinite;
}
.lds-ellipsis div:nth-child(3) {
    left: 26px;
    animation: lds-ellipsis2 0.6s infinite;
}
.lds-ellipsis div:nth-child(4) {
    left: 45px;
    animation: lds-ellipsis3 0.6s infinite;
}
@keyframes lds-ellipsis1 {
    0% {
        transform: scale(0);
    }
    100% {
        transform: scale(1);
    }
}
@keyframes lds-ellipsis3 {
    0% {
        transform: scale(1);
    }
    100% {
        transform: scale(0);
    }
}
@keyframes lds-ellipsis2 {
    0% {
        transform: translate(0, 0);
    }
    100% {
        transform: translate(19px, 0);
    }
}
</style>

