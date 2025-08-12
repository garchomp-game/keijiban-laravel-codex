# DB Schema

- users(id, name, email unique, password, ...)
- threads(id, user_id, title, body, ...)
- posts(id, thread_id, user_id, body, ...)
- reactions(id, post_id, user_id, type)

Relations:  
User hasMany Threads/Posts/Reactions  
Thread belongsTo User; hasMany Posts  
Post belongsTo Thread & User; hasMany Reactions  
Reaction belongsTo Post & User
