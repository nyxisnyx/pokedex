CREATE TABLE evolution AS
WITH RECURSIVE evolution_chain AS (
    SELECT id, 
    	name, 
    	evolutionPrev, 
    	evolutionNext, 
    	name AS full_chain, 
    	id AS start_id, 
    	1 AS step
    FROM `pokemon` 
    
    UNION ALL
    
    SELECT p.id, 
    	p.name, 
    	p.evolutionPrev, 
    	p.evolutionNext, 
    	CONCAT(ec.full_chain, ',', p.name) AS full_chain,
    	ec.start_id,
    	ec.step + 1
    FROM `pokemon` p 
    INNER JOIN evolution_chain ec ON p.evolutionPrev = ec.id
),

evolution AS (
    SELECT ec1.id, ec2.full_chain
    FROM evolution_chain ec1
    INNER JOIN evolution_chain ec2 ON ec1.start_id = ec2.start_id
    WHERE ec2.step = (SELECT MAX(step) FROM evolution_chain ec3 WHERE ec3.start_id = ec1.start_id)
)

SELECT * FROM evolution;