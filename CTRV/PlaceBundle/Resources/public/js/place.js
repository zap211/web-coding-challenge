/**
 * Retourne la liste de place selon la rue spécifiée 
 */
function loadSearchedPlaces (urlAction, p_searchText, ppage, container_id ) {
	$('#'+container_id).html('<div class="ctrv-loader"></div>');
	
	$.post(urlAction,
		      {
				searchText:p_searchText,
				page:ppage
				
		      },
		      function (data) {
		    	  $('#'+container_id).html(data);
		      },
		      'html'
		  ); 
}




/**
 * Retourne la liste des place selon le type et la page spécifiée
 */
function loadPlacesByType (urlAction,pplaceTypeId,ppage,container) {
	container.html('<div class="ctrv-loader"></div>');
	
	$.post(urlAction,
		      {
				placeTypeId:pplaceTypeId,
				page:ppage
		      },
		      function (data) {
		    	  container.html(data);
		      },
		      'html'
		  ); 
}


/**
 * Retourne la liste des places
 */
function loadPlaces (urlAction,ppage,container) {
	container.html('<div class="ctrv-loader"></div>');
	
	$.post(urlAction,
		      {
				page:ppage
		      },
		      function (data) {
		    	  container.html(data);
		      },
		      'html'
		  ); 
}
/**
 * Supprime une place
 */
function deletePlace (urlAction, tr_elem) {
	$.post(urlAction,
	      {
	      },
	      function (data) {
	    	  if(data.result){
	    		  tr_elem.remove();
	    	  }
	      },
	      'json'
	); 
}


function deleteListPlace (urlAction, pselectedIds) {
	$.post(urlAction,
	      {
			selectedIds: JSON.stringify(pselectedIds)
	      },
	      function (data) {
	    	  if(data.result){
	    		  location.reload();
	    	  }
	      },
	      'json'
	); 
}

/**
 * Supprime un type de place
 */
function deletePlaceType (urlAction, tr_elem) {
	$.post(urlAction,
	      {
	      },
	      function (data) {
	    	  if(data.result){
	    		  tr_elem.remove();
	    	  }
	      },
	      'json'
	); 
}