CREATE TABLE [dbo].[Metric]
(
	[ID] INT NOT NULL PRIMARY KEY IDENTITY, 
    [Label] NVARCHAR(50) NOT NULL DEFAULT 'UNKNOWN', 
    [Created] DATETIME NOT NULL DEFAULT getdate(), 
    [Value] REAL NOT NULL DEFAULT 0.00, 
    [Type] NVARCHAR(50) NOT NULL DEFAULT 'Gauge', 
    [Units] NVARCHAR(50) NOT NULL DEFAULT 'NA', 
    [Service] NVARCHAR(50) NULL, 
    [Region] NVARCHAR(50) NULL, 
    [InstanceID] INT NULL, 
    [ClusterID] INT NULL, 
    [ProjectID] INT NULL, 
    [Created By] NVARCHAR(50) NULL
)

GO

CREATE INDEX [IX_Metric_Created] ON [dbo].[Metric] ([Created])

GO

INSERT INTO Metric VALUES ('CPUUtilization', getdate(), 48.852, 'Gauge', 'Percent', 'EC2','us-west-2', 0,0,0,'rajamani');
