--
-- Author:        Pierre-Henry Soria <ph7software@gmail.com>
-- Copyright:     (c) 2013-2019, Pierre-Henry Soria. All Rights Reserved.
-- License:       MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
--

ALTER TABLE pH7_AdminsAttemptsLogin ADD UNIQUE KEY (ip);
ALTER TABLE pH7_MembersAttemptsLogin ADD UNIQUE KEY (ip);
ALTER TABLE pH7_AffiliatesLogLogin ADD UNIQUE KEY (ip);
