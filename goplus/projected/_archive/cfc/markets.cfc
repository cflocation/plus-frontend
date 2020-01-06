<cfcomponent hint="User Details" output="yes">

	<cffunction name="getMarkets" access="public">
		<cfargument name="userid" default="0">
		
		<cfquery name="nationalrep" datasource="showseeker">
			select * from userroles where userid = '#userid#' and roleid = 15
		</cfquery>
		
		<cfif nationalrep.recordCount EQ 0>
		
			<cfquery name="markets" datasource="showseeker">
				SELECT  			regions.id, 
									regions.name
				
				FROM 				useroffices
				
				INNER JOIN 			offices 
				ON 					offices.id = useroffices.officeid
				
				INNER JOIN 			marketzones 
				ON 					marketzones.marketid = offices.regionid
				
				INNER JOIN 			regions 
				ON 					regions.id = marketzones.marketid
				
				WHERE 				userid = '#userid#'
				
				AND 				regions.deletedat IS NULL
				GROUP BY 			regions.id
				ORDER BY  			regions.name
			</cfquery>
		<cfelse><!--- //NATIONAL REP --->
			<cfquery name="markets" datasource="showseeker">			
				SELECT  			regions.id, 
									regions.name
				
				FROM 				users  

				INNER JOIN 			corporations
				ON					users.corporationid = corporations.id
				
				INNER JOIN			regions
				ON					regions.corporationid = corporations.id
				
				WHERE 				users.id = '#userid#'
				
				AND 				regions.deletedat IS NULL
				ORDER BY  			regions.name
			</cfquery>
		</cfif>
					    
		    
	    	<cfreturn markets>
	</cffunction>

</cfcomponent>