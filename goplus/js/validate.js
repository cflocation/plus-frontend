function checkIt(evt) {
    evt = (evt) ? evt : window.event
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        status = "This field accepts numbers only."
        return false
    }
    status = ""
    return true
}



function validateForm(){

	var x = $("#seller_name").val();
	
	if(x === ""){
		msg = "All fields are required (Seller Info Name)";
		return false;
	}
	
	if(x.indexOf('&') != -1 ){
		msg = 'An ampersand (&) was found at the Seller Info Name and it is not accepted by the XML parser. \n Please replace it.';
		return false; 
	}
	
	if(x.indexOf('"') != -1 ){
		msg = 'A quotation mark (") was found at the Seller Info Name and it is not accepted by the XML parser. \n Please replace it.';
		return false; 
	} 
	
	x = $("#seller_company").val();
	if(x === ""){
		msg = "All fields are required (Seller Info Company)");
		return false;
	}
	
	if(x.indexOf('&') != -1 ){
		msg = 'An ampersand (&) was found at the Seller Info Company field and it is not accepted by the XML parser. \n Please replace it.';
		return false; 
	}
	
	if(x.indexOf('"') != -1 ){
		msg = 'A quotation mark (") was found at the Seller Info Company field and it is not accepted by the XML parser. \n Please replace it.';
		return false; 
	} 
  

	x = $("#seller_office").val();
	if (x === ""){
		msg = "All fields are required (Seller Info Office)";
		return false;
	}
	
	if(x.indexOf('&') != -1 ){
		msg = 'An ampersand (&) was found at the Seller Info Office field and it is not accepted by the XML parser. \n Please replace it.';
		return false; 
	}
	
	if(x.indexOf('"') != -1 ){
		msg = 'A quotation mark (") was found at the Seller Info Office field and it is not accepted by the XML parser. \n Please replace it.';
		return false; 
	}  
	
	x = $("#seller_phone1").val();
	if(x==null || x==""){
		msg = "Please make sure your phone number is valid";
		return false;
	}  

	x = $("seller_phone2").val();
	if(x === ""){
		msg = "Please make sure your phone number is valid";
		return false;
	}
  
	x = $("seller_phone3").val();
	if (x === ""){
		msg = "Please make sure your phone number is valid";
		return false;
	}
  
  
  var zz = $("#seller_phone1").val().length + $("#seller_phone2").val().length + $("#seller_phone3").val().length;
 
	if(parseInt(zz) != 10) { 
		msg = "Please make sure your phone number is valid";
 		return false;
	}
  
	x 				= $("#seller_email").val();
	var atpos	= x.indexOf("@");
	var dotpos	= x.lastIndexOf(".");
	
	if(atpos < 1 || dotpos<atpos+2 || dotpos+2>=x.length){
		msg = "Not a valid e-mail address";
		return false;
	}
  
	if(x.indexOf('&') != -1 ){
		msg = 'An ampersand (&) was found at the Seller Email field and it is not accepted by the XML parser. \n Please replace it.';
		return false; 
	}
	
	if(x.indexOf('"') != -1 ){
		msg = 'A quotation mark (") was found at the Seller Email field and it is not accepted by the XML parser. \n Please replace it.';
		return false; 
	}
  
  
  

// --------------- Buyer Name
  
	x = $("#sbname").val();
	if(x === ""){
		msg = "All fields are required (Buyer Name)";
		return false;
	}
	
	if(x.indexOf('&') != -1 ){
		msg = 'An ampersand (&) was found at the Buyer Name field and it is not accepted by the XML parser. \n Please replace it.';
		return false; 
	}
	
	if(x.indexOf('"')  != -1 ){
		msg = 'A quotation mark (") was found at the Buyer Name field and it is not accepted by the XML parser. \n Please replace it.';
		return false; 
	}   



// --------------- Buyer Company

	x = $("#sbcompany").val();
	
	if(x === ""){
		msg = "All fields are required (Buyer Company)";
		return false;
	}
	
	if(x.indexOf('&') != -1 ){
		msg = 'An ampersand (&) was found at the Buyer Company field and it is not accepted by the XML parser. \n Please replace it.';
		return false; 
	}
	
	if(x.indexOf('"') != -1 ){
		msg = 'A quotation mark (") was found at the Buyer Company field and it is not accepted by the XML parser. \n Please replace it.';
		return false; 
	}    
  
  
// --------------- Buyer Office  

	x = $("#boffice").val();
	
	if(x === ""){
		msg = "All fields are required (Buyer Office)";
		return false;
	}
	
	if(x.indexOf('&') != -1 ){
		msg = 'An ampersand (&) was found at the Buyer Office field and it is not accepted by the XML parser. \n Please replace it.';
		return false; 
	}
	
	if(x.indexOf('"') != -1 ){
		msg = 'A quotation mark (") was found at the Buyer Office field and it is not accepted by the XML parser. \n Please replace it.';
		return false; 
	}   
  
  
// --------------- Buyer Advertiser   
	x = $("#badvertiser").val();
	
	if(x === ""){
		msg = "All fields are required (Buyer Advertiser)";
		return false;
	}

	if(x.indexOf('&') != -1 ){
		msg = 'An ampersand (&) was found at the Buyer Advertiser field and it is not accepted by the XML parser. \n Please replace it.';
		return false; 
	}

	if(x.indexOf('"') != -1 ){
		alert('A quotation mark (") was found at the Buyer Advertiser field and it is not accepted by the XML parser. \n Please replace it.';
		return false; 
	}    
  
  
// --------------- Buyer Product  
	x = $("#bproduct").val();
	
	if(x === ""){
		msg = "All fields are required (Buyer Product)";
	  return false;
	}
	  
	 if(x.indexOf('&') != -1 ){
		msg = 'An ampersand (&) was found at the Buyer Product field and it is not accepted by the XML parser. \n Please replace it.';
		return false; 
	}
	
	if(x.indexOf('"') != -1 ){
		msg = 'A quotation mark (") was found at the Buyer Product field and it is not accepted by the XML parser. \n Please replace it.';
	  return false; 
	}    
  
 	$('#container,#header').css({'display':'none'});
	$('.centreElementWithJQuery').css({'display':'inline'});
	setTimeout(function(){window.close();}, 30000);
	
}