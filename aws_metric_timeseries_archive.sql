DECLARE @ID INT;
while EXISTS(select * from Metric where Created < '2014-01-01')
BEGIN
select @ID=ID from Metric where Created < '2014-01-01'
INSERT INTO Archive (Label, Created, Value, Type, Units, Service, Region, InstanceID, ClusterID, ProjectID, [Created By])
SELECT Label, Created, Value, Type, Units, Service, Region, InstanceID, ClusterID, ProjectID, [Created By] from Metric WHERE ID = @ID;
DELETE FROM Metric where Created < '2014-01-01';
select @ID=ID from Metric where Created < '2014-01-01';
END
