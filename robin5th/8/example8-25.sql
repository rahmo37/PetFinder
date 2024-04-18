SELECT author,title FROM classics
 WHERE MATCH(author,title)
 AGAINST('+charles -species' IN BOOLEAN MODE);
SELECT author,title FROM classics
 WHERE MATCH(author,title)
 AGAINST('"origin of"' IN BOOLEAN MODE);



-- This SQL query is searching within a table named classics for records where the author and title columns match certain criteria defined in a MATCH ... AGAINST clause, using MySQL's full-text search in boolean mode.

-- Here's a breakdown:

-- SELECT author,title FROM classics: This part of the query specifies that you want to retrieve the author and title columns from rows in the classics table.

-- MATCH(author,title) AGAINST('+charles -species' IN BOOLEAN MODE): This is where the full-text search happens. It's looking for rows where the author or title columns contain the word 'charles' but not 'species'.

-- The + sign before 'charles' indicates that 'charles' must be present in either the author or title for a row to be considered a match.
-- The - sign before 'species' means rows containing the word 'species' in these columns will be excluded from the results.
-- IN BOOLEAN MODE specifies that the search should be performed using boolean mode, which allows for the use of these kinds of operators to refine searches.
-- In simple terms, this query asks the database to give you the author and title of books in the classics table where the author or title includes 'charles' but does not include 'species'. This is particularly useful for searching text with specific inclusion and exclusion criteria.