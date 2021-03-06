<?php 

namespace CTRV\CommonBundle\DependencyInjection;

Class Constants {
	
	//temps d'attente apres saisie dans un champs de recherche
	const TIME_BEFORE_SEARCH = 450;

	//nombre d'éléments par page
	const places_number_per_page = 10;
	const places_type_number_per_page = 10;
	const places_with_same_address_number_per_page  = 100;
	const places_without_description_number_per_page = 10;
	const places_without_lat_lng_number_per_page = 10;
	const places_search_number_per_page = 10;
	const placesComment_number_per_page = 10;
	
	const agendas_number_per_page = 10;
	const agendasComment_number_per_page = 10;
	
	const events_number_per_page = 10;
	const eventsComment_number_per_page = 10;
	
	const publicmessage_number_per_page = 10;
	const users_number_per_page = 10;
	const users_search_number_per_page = 10;
	const groupes_number_per_page = 10;
	const mailstype_number_per_page = 10;
	const abuse_number_per_page = 10;
	const city_number_per_page = 10;
// 	const comments_number_per_page = 10;
	
	//date
	const DATE_FORMAT = "d/m/Y H:i:s";
	
	//web service
	const WEB_SERVICE_PATH = "http://webservice.com/v1/";
	
	//places
	const IMPORT_FILE_PATH = "uploads/places";
	
	//map api
	const GOOGLE_MAP_API_URL = "http://maps.googleapis.com/maps/api/geocode/json?address=";
	const OPEN_STREET_API_URL = 'http://nominatim.openstreetmap.org/search?q=';
	
	//role
	const ROLE_USER = "ROLE_USER";
	const ROLE_ADMIN = "ROLE_ADMIN";
	const ROLE_PROFESSIONNEL = "ROLE_PROFESSIONNEL";
	
	const TYPE_ENTIY_EVENT = "EVENT";
	const TYPE_ENTIY_AGENDA = "AGENDA";
	const TYPE_ENTIY_PLACE = "PLACE";
	
	//etat filtre
	const STATE_USER_FILTER_ALL = "TOUS";
	const STATE_USER_FILTER_BLOCKED = "BLOQUES";
	const STATE_USER_FILTER_DISABED = "DESACTIVES";
	
	//abus
	const EVENT_COMMENT_ABUSE = "EVENT";
	const PLACE_COMMENT_ABUSE = "PLACE";
	const MESSAGEPUBLIC_COMMENT_ABUSE = "MESSAGEPUBLIC";
}