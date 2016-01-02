DECLARE @ID INT;
select @ID=ID from Metric where Created < '2014-01-01'
while EXISTS(select * from Metric where Created < '2014-01-01')
BEGIN
INSERT INTO Archive (Label, Created, Value, Type, Units, Service, Region, InstanceID, ClusterID, ProjectID, [Created By])
SELECT Label, Created, Value, Type, Units, Service, Region, InstanceID, ClusterID, ProjectID, [Created By] from Metric WHERE ID = @ID;
DELETE FROM Metric where Created < '2014-01-01';
select @ID=ID from Metric where Created < '2014-01-01';
END
