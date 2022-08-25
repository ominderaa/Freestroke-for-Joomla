/*
	all memebers allowed to swim in the programs of a particual meet
*/
select ev.id, mb.id, lastname, firstname, mb.gender, ev.gender, birthdate, TIMESTAMPDIFF(YEAR,birthdate,'2017-12-13') AS age, 
  ev.programnumber, ev.minage, ev.maxage, ss.distance, ss.name
from dvs_freestroke_members mb
join dvs_freestroke_events ev
inner join dvs_freestroke_swimstyles ss on ss.id = ev.swimstyles_id
where isactive is null or isactive = 1
  and ev.id in (
    select ev2.id
    from dvs_freestroke_meets m2
    inner join dvs_freestroke_meetsessions ms2 on m2.id = ms2.meets_id
    inner join dvs_freestroke_events ev2 on ev2.meets_id = m2.id and ev2.sessionnumber = ms2.sessionnumber
    where m2.id = 226
  )
  and ev.gender = mb.gender
  and ev.minage <= TIMESTAMPDIFF(YEAR,birthdate,'2017-12-13')
  and ev.maxage >= TIMESTAMPDIFF(YEAR,birthdate,'2017-12-13')
order by ev.programnumber asc, birthdate desc
;