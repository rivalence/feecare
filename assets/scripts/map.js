export default class Carte {
    constructor(map){
        this.map = map

        if(this.map){
            this.init()
        }
    }

    init(){
        /* Les options pour afficher la France */
        const mapOptions = {
            center: [46.225, 0.132],
            zoom: 7
        }
        const carte = new L.map('map', mapOptions)

        //Couche de la carte
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        minZoom: 5,
        maxZoom: 20,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(carte);

        //Geolocalisation
        if ("geolocation" in  navigator) {
            navigator.geolocation.getCurrentPosition((position)=>{
                const current_position = new L.LatLng(position.coords.latitude, position.coords.longitude);
                carte.setZoom(16);
                carte.panTo(current_position);

                //Controle de géolocalisation
                L.Control.geocoder().addTo(carte);

                //Routing
                L.Routing.control({
                    // waypoints: [
                    //     L.latLng(current_position),
                    //     L.latLng(57.6792, 11.949)
                    // ],
                    router: new L.Routing.osrmv1({
                        language: 'fr'
                    }),
                    routeWhileDragging: true,
                    geocoder: L.Control.Geocoder.nominatim()
                }).addTo(carte);

                //Affichage d'un centre sur la map
                const centres = [].slice.call(document.querySelectorAll("#centre"));
                if (centres) {
                    centres.map((element) => {
                        element.addEventListener("click", (e) => {
                            e.preventDefault();
                            const url = element.href;
                            fetch(url)
                            .then(res => res.json())
                            .then(res => {
                                //Rajout du marqueur du centre selectionné
                                const centre_position = new L.LatLng(res.lat, res.lng);
                                const marker = L.marker(centre_position).addTo(carte);

                                marker.bindPopup("<b>Centre de "+ res.nom+ "</b><br>"+ res.lat +", "+ res.lng);
                                carte.panTo(centre_position);
                            })
                        })
                    })
                }

                //Affichage itineraire
                const itineraires = [].slice.call(document.querySelectorAll("#itineraire"));
                if(itineraires) {
                    itineraires.map((element) => {
                        element.addEventListener("click", (e) => {
                            e.preventDefault();
                            const url = element.href;
                            fetch(url)
                            .then(res => res.json())
                            .then(res => {
                                const centre_position = new L.LatLng(res.lat, res.lng);

                                //Routing
                                L.Routing.control({
                                    waypoints: [
                                        L.latLng(current_position),
                                        L.latLng(centre_position)
                                    ],
                                    router: new L.Routing.osrmv1({
                                        language: 'fr',
                                        profile: 'car',
                                    }),
                                    routeWhileDragging: true,
                                    geocoder: L.Control.Geocoder.nominatim()
                                }).addTo(carte);

                                //Rajout du marqueur du centre selectionné
                                const marker = L.marker(centre_position).addTo(carte);
                                marker.bindPopup("<b>Centre de "+ res.nom+ "</b><br>"+ res.lat +", "+ res.lng);
                            })
                        })
                    })
                }
            }, (positionError)=>{
                alert('Erreur de géolocalisation');
            })
        }
        else {
            //Controle de géolocalisation
            L.Control.geocoder().addTo(carte);

            //Routing
            L.Routing.control({
                // waypoints: [
                //     L.latLng(current_position),
                //     L.latLng(57.6792, 11.949)
                // ],
                router: new L.Routing.osrmv1({
                    language: 'fr'
                }),
                routeWhileDragging: true,
                geocoder: L.Control.Geocoder.nominatim()
            }).addTo(carte);
        }
        //Afficher la map
        // const carte = L.carte('carte').setView([51.505, -0.09], 15);
        
    }
}