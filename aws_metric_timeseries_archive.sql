DECLARE @ID INT;
while EXISTS(SELECT * FROM Metric WHERE Created < '2014-01-01')
BEGIN
SELECT @ID=ID FROM Metric WHERE Created < '2014-01-01'
INSERT INTO Archive (Label, Created, Value, Type, Units, Service, Region, InstanceID, ClusterID, ProjectID, [Created By])
SELECT Label, Created, Value, Type, Units, Service, Region, InstanceID, ClusterID, ProjectID, [Created By] from Metric WHERE ID = @ID;
DELETE FROM Metric WHERE ID=@ID; 
SELECT @ID=ID FROM Metric WHERE Created < '2014-01-01';
END

-- the archival policy here is date based but any criteria for selection can be specified.
