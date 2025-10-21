SELECT c.id, c.link, t.id, t.name 
FROM channel c 
JOIN tag_channel tc ON c.id = tc.channel_id
JOIN tag t ON tc.tag_id = t.id
ORDER BY c.id, t.name;

