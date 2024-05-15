INSERT INTO populations (id, name) VALUES (1, 'Employees');
INSERT INTO population_fields (id, type, required, isunique, multi, sensitive, name, dname, population_id) VALUES
	(1, 'text', true, true, false, false, 'employeeid', 'Employee ID', 1),
	(2, 'text', true, false, false, false, 'fname', 'First Name', 1),
	(3, 'text', true, false, false, false, 'lname', 'Last Name', 1),
	(4, 'date', true, false, false, true, 'bdate', 'Birth Date', 1),
	(5, 'text', true, false, false, true, 'bplace', 'Birth Place', 1),
	(6, 'email', true, true, false, false, 'email', 'Email Address', 1);

INSERT INTO populations (id, name) VALUES (2, 'External Contractors');
INSERT INTO population_fields (id, type, required, isunique, multi, sensitive, name, dname, population_id) VALUES
	(7, 'text', true, true, false, false, 'employeeid', 'Employee ID', 2),
	(8, 'text', true, true, false, false, 'contractorid', 'Contractor ID', 2),
	(9, 'text', true, false, false, false, 'fname', 'First Name', 2),
	(10, 'text', true, false, false, false, 'lname', 'Last Name', 2),
	(11, 'email', true, true, false, false, 'email', 'Email Address', 2);


INSERT INTO users (id, population_id, username, password) VALUES (1, 1, 'testemployee', '$argon2i$v=19$m=1024,t=2,p=2$YzJBSzV4TUhkMzc3d3laeg$zqU/1IN0/AogfP4cmSJI1vc8lpXRW9/S0sYY2i2jHT0');
INSERT INTO user_values (user_id, field_id, value) VALUES
	(1, 1, 'A00513'),
	(1, 2, 'John'),
	(1, 3, 'Doe'),
	(1, 4, '1994-01-23'),
	(1, 5, 'Carignan, QC, Canada'),
	(1, 6, 'jdoe@example.com');

INSERT INTO users (id, population_id, username, password) VALUES (2, 2, 'testcontractor', '$argon2i$v=19$m=1024,t=2,p=2$YzJBSzV4TUhkMzc3d3laeg$zqU/1IN0/AogfP4cmSJI1vc8lpXRW9/S0sYY2i2jHT0');
INSERT INTO user_values (user_id, field_id, value) VALUES
	(2, 7, 'A00513'),
	(2, 8, 'fs-335'),
	(2, 9, 'James'),
	(2, 10, 'Doe'),
	(2, 11, 'jdoe@example.com');
