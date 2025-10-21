use acround;
SELECT c.id, c.link, t.id, t.name 
FROM acround.channel c 
JOIN acround.tag_channel tc ON c.id = tc.channel_id
JOIN acround.tag t ON tc.tag_id = t.id
ORDER BY c.id, t.name;

