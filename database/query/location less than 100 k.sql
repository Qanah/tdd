-- by miles 3959 kilometers 6371

SELECT 
id, 
(
   3959 *
   acos(cos(radians(62)) * 
   cos(radians(lat)) * 
   cos(radians(lng) - 
   radians(168)) + 
   sin(radians(62)) * 
   sin(radians(lat )))
) AS distance 
FROM markers 
HAVING distance < 100 
ORDER BY distance LIMIT 0, 20;
