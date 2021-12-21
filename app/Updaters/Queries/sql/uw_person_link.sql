UPDATE contacts c
JOIN shared.uw_persons p
ON c.employeeid = p.employeeid OR c.uwnetid = p.uwnetid
SET c.person_id = p.person_id,
    c.uwnetid = p.uwnetid,
    c.studentno = p.studentno,
    c.employeeid = p.employeeid,
    c.firstname = p.firstname,
    c.lastname = p.lastname,
    c.email = p.email;
