/* Get the most popular countries on the site */
SELECT       country, count(*) as Number
FROM         'profile'
GROUP BY     country
ORDER BY     Number DESC;


-- SELECT       count(*) as Num
-- FROM         'profile'
-- WHERE        interests LIKE '%Analytics%';


/* Get the number of users who have a certain technology listed in their skills */
-- SELECT      SUM(case when interests LIKE '%Machine Learning%' then 1 else 0 end) AS MLUsers,
--             SUM(case when interests LIKE '%CSS%' AND interests LIKE '%CSS%' then 1 else 0 end) AS WebUsers,
--             SUM(case when interests LIKE '%Data Analytics%' then 1 else 0 end) AS DataAnalyticsUsers,
--             SUM(case when interests LIKE '%C++%' and interests LIKE '%C#%' then 1 else 0 end) AS CUsers
-- FROM        profile;


/* Get the salary brackets across all users */
-- SELECT      salary_bracket, count(*) as num
-- FROM        profile
-- GROUP BY    salary_bracket
-- ORDER BY    salary_bracket ASC;


/* Get the salary brackets for a technology */
-- SELECT      salary_bracket, count(*) as num
-- FROM        profile
-- WHERE       interests LIKE '%Machine Learning%'
-- GROUP BY    salary_bracket
-- ORDER BY    num DESC;
