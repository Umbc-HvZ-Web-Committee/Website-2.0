DELIMITER //

CREATE FUNCTION PlayerState(n INT)
  RETURNS VARCHAR(20)

  BEGIN
    DECLARE s VARCHAR(20);

    IF n > 0 THEN SET s = 'Human';
    ELSEIF n = 0 THEN SET s = 'Deceased';
    ELSE SET s = 'Zombie';
    END IF;

    RETURN s;
  END //

DELIMITER ;