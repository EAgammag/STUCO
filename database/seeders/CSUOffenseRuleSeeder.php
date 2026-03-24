<?php

namespace Database\Seeders;

use App\Models\OffenseRule;
use Illuminate\Database\Seeder;

class CSUOffenseRuleSeeder extends Seeder
{
    /**
     * Seed the database with Cagayan State University Student Manual offense rules.
     *
     * This seeder implements the complete offense rules from the CSU Student Manual,
     * including major offenses, minor offenses, and ICT policy violations.
     */
    public function run(): void
    {
        $offenseRules = [
            // A. LIQUOR AND DRUGS
            [
                'code' => 'LD-A1',
                'title' => 'Entering University Under Influence of Liquor',
                'description' => 'Entering the University under the influence of liquor or intoxicated drink.',
                'category' => 'Behavioral',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: 5 class days suspension; 2nd: Dismissal',
            ],
            [
                'code' => 'LD-A2',
                'title' => 'Possessing or Selling Liquor Within University',
                'description' => 'Possessing or selling liquor within the university premises.',
                'category' => 'Behavioral',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: 10 class days suspension; 2nd: Dismissal',
            ],
            [
                'code' => 'LD-A3',
                'title' => 'Prohibited Drug Possession/Sale/Use',
                'description' => 'Possessing, selling and using prohibited drugs in any form within the university.',
                'category' => 'Safety',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: Dismissal',
            ],

            // B. MASS ACTION AND SUBVERSIVE ACTIVITIES
            [
                'code' => 'MA-B1',
                'title' => 'Unapproved Mass Actions',
                'description' => 'Joining unapproved mass actions, subversive activities or instigating rallies, strikes, boycotts, demonstrations and other forms of unapproved group action, which create disorder in the university.',
                'category' => 'Behavioral',
                'severity_level' => 'Major',
                'standard_sanction' => '1st: Reprimand; 2nd: 5 class days suspension; 3rd: Dismissal',
            ],
            [
                'code' => 'MA-B2',
                'title' => 'Distributing Subversive Materials',
                'description' => 'Posting, disseminating, distributing and circulating leaflets against any person or the university or any printed matter that tend to instigate subversion towards the government and cause disturbance and chaos to the University.',
                'category' => 'Behavioral',
                'severity_level' => 'Major',
                'standard_sanction' => '1st: Reprimand; 2nd: 5 class days suspension; 3rd: Dismissal',
            ],
            [
                'code' => 'MA-B3',
                'title' => 'Unauthorized Posting of Materials',
                'description' => 'Unauthorized posting of any printed material.',
                'category' => 'Procedural',
                'severity_level' => 'Minor',
                'standard_sanction' => '1st: Reprimand; 2nd: 5 class days suspension; 3rd: Dismissal',
            ],
            [
                'code' => 'MA-B4',
                'title' => 'Joining Unaccredited Organizations',
                'description' => 'Organizing and joining any organization not accredited by the University or fraternity, sorority, subversive groups which create disorder and disciplinary problems to the University.',
                'category' => 'Procedural',
                'severity_level' => 'Major',
                'standard_sanction' => '1st: 5 class days suspension; 2nd: Dismissal',
            ],
            [
                'code' => 'MA-B5',
                'title' => 'Disrupting Classes and Barricading',
                'description' => 'Disrupting classes and barricading the University entrance and other places in the University.',
                'category' => 'Behavioral',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: 5 class days suspension; 2nd: Dismissal',
            ],
            [
                'code' => 'MA-B6',
                'title' => 'Tarnishing University Name',
                'description' => 'Any act which tarnishes the name of the University or any violation of the laws of decency.',
                'category' => 'Behavioral',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: Dismissal',
            ],

            // C. DEADLY AND DANGEROUS WEAPONS
            [
                'code' => 'DW-C1',
                'title' => 'Unauthorized Possession of Bladed Weapons',
                'description' => 'Unauthorized possession of deadly bladed weapons within the university premises.',
                'category' => 'Safety',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: 5 class days suspension; 2nd: Dismissal',
            ],
            [
                'code' => 'DW-C2',
                'title' => 'Unauthorized Possession of Firearms/Explosives',
                'description' => 'Unauthorized possession of fire-arms and explosives within the school premises.',
                'category' => 'Safety',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: Dismissal',
            ],

            // D. EXTORTION/BRIBERY
            [
                'code' => 'EB-D1',
                'title' => 'Extortion or Bribery',
                'description' => 'Forcibly giving or asking money from anybody or any act of bribery to gain favor in violation of standard instruction.',
                'category' => 'Behavioral',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: Dismissal',
            ],
            [
                'code' => 'EB-D2',
                'title' => 'Misrepresentation of University',
                'description' => 'Misrepresentation of the university like using the name of the University in illegal solicitations.',
                'category' => 'Behavioral',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: 5 class days suspension; 2nd: Dismissal',
            ],
            [
                'code' => 'EB-D3',
                'title' => 'Unauthorized Fund Raising',
                'description' => 'Unauthorized selling of tickets, and/or initiating or participating in fund raising campaigns without prior authorization/approval from designated university authorities and officials.',
                'category' => 'Procedural',
                'severity_level' => 'Moderate',
                'standard_sanction' => '1st: 5 class days suspension; 2nd: Dismissal',
            ],

            // E. VIOLENCE, PHYSICAL ASSAULT OR INJURY
            [
                'code' => 'VP-E1',
                'title' => 'Fighting Within University Premises',
                'description' => 'Fighting within the University premises.',
                'category' => 'Behavioral',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: 5 class days suspension; 2nd: Dismissal',
            ],
            [
                'code' => 'VP-E2',
                'title' => 'Violence Resulting in Grave Injury',
                'description' => 'Resorting to any act of violence that results to grave and serious injury.',
                'category' => 'Safety',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: 5 class days suspension; 2nd: Dismissal',
            ],
            [
                'code' => 'VP-E3',
                'title' => 'Violence Resulting in Death',
                'description' => 'Any act of violence that result to death.',
                'category' => 'Safety',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: Expulsion',
            ],
            [
                'code' => 'VP-E4',
                'title' => 'Bringing Trouble Makers to Campus',
                'description' => 'Bringing "trouble makers" within the university premises for purposes of committing a crime of felony.',
                'category' => 'Safety',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: Dismissal',
            ],
            [
                'code' => 'VP-E5',
                'title' => 'Gross Misconduct in Gatherings',
                'description' => 'Gross misconduct and unruly behavior during student meetings, assemblies and programs.',
                'category' => 'Behavioral',
                'severity_level' => 'Moderate',
                'standard_sanction' => '1st: Reprimand; 2nd: 5 class days suspension; 3rd: Dismissal',
            ],
            [
                'code' => 'VP-E6',
                'title' => 'Endangering Health or Safety',
                'description' => 'Any other misbehavior or misconduct which may endanger or threaten the health or safety of an individual in the university premises or which may adversely affect students\' welfare as members of the academic community.',
                'category' => 'Safety',
                'severity_level' => 'Major',
                'standard_sanction' => '1st: Reprimand; 2nd: 5 class days suspension; 3rd: Dismissal',
            ],
            [
                'code' => 'VP-E7',
                'title' => 'Hazing',
                'description' => 'Hazing or inflicting physical or mental harm and/or unlawful initiation for admission to any organization that tends to injure, degrade or humiliate another even in mere conspiracy.',
                'category' => 'Safety',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: Expulsion',
            ],

            // F. STEALING
            [
                'code' => 'ST-F8',
                'title' => 'Theft of Property',
                'description' => 'Stealing any property within the University.',
                'category' => 'Behavioral',
                'severity_level' => 'Major',
                'standard_sanction' => '1st: Reprimand + replacement; 2nd: 5 days suspension + replacement; 3rd: Dismissal + replacement',
            ],
            [
                'code' => 'ST-F9',
                'title' => 'Illegally Picking University Resources',
                'description' => 'Illegally picking fruits, flowers, and any other produce which are within the University premises.',
                'category' => 'Procedural',
                'severity_level' => 'Minor',
                'standard_sanction' => '1st: Plant 5 trees + community service; 2nd: Plant 10 trees + 5 days suspension; 3rd: Dismissal',
            ],

            // G. SLANDER, LIBEL, RUMOR MONGERING
            [
                'code' => 'SL-G1',
                'title' => 'Defamation (Written/Oral)',
                'description' => 'Circulating written or oral and/or publishing false, derogatory, vulgar, defamatory, slanderous, and libelous words, statements, remarks against any student, faculty or employee.',
                'category' => 'Behavioral',
                'severity_level' => 'Major',
                'standard_sanction' => '1st: 5 class days suspension; 2nd: Dismissal',
            ],
            [
                'code' => 'SL-G2',
                'title' => 'Online Defamation',
                'description' => 'Circulating online false, derogatory, vulgar, defamatory, slanderous, and libelous words, statements, remarks against any student, faculty or employee.',
                'category' => 'Technology',
                'severity_level' => 'Major',
                'standard_sanction' => '1st: 5 class days suspension; 2nd: Dismissal',
            ],

            // H. SCANDALOUS ACTS
            [
                'code' => 'SC-H1',
                'title' => 'Acts of Lasciviousness',
                'description' => 'Acts of lasciviousness.',
                'category' => 'Behavioral',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: Dismissal',
            ],
            [
                'code' => 'SC-H2',
                'title' => 'Lewd or Indecent Conduct',
                'description' => 'Disorderly, lewd, indecent or obscene conduct or language within and outside the University.',
                'category' => 'Behavioral',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: 5 class days suspension; 2nd: Dismissal',
            ],
            [
                'code' => 'SC-H3',
                'title' => 'Sexual Harassment',
                'description' => 'Sexual Harassment in any form, as defined according to R.A. 7877.',
                'category' => 'Behavioral',
                'severity_level' => 'Severe',
                'standard_sanction' => 'Refer to Anti-Abuse Committee of the University',
            ],
            [
                'code' => 'SC-H4',
                'title' => 'Illicit Relationship',
                'description' => 'Illicit Relationship.',
                'category' => 'Behavioral',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: Dismissal',
            ],

            // I. VANDALISM/LITTERING
            [
                'code' => 'VL-I1',
                'title' => 'Vandalism and Property Destruction',
                'description' => 'Committing any act of vandalism, destroying or any form of mutilation- writing or drawing on walls and pieces of furniture; tearing of pages of library books, magazines and other references, breaking glass windows, showcases, cabinets, connection or disconnection of electrical wires and plumbing device without permission from authorities concerned, improper use of tables and chairs, tools, computers and machines.',
                'category' => 'Procedural',
                'severity_level' => 'Moderate',
                'standard_sanction' => '1st: Reprimand; 2nd: 5 class days suspension; 3rd: Dismissal',
            ],
            [
                'code' => 'VL-I2',
                'title' => 'Littering',
                'description' => 'Littering pieces of paper and other materials in the classroom and within the vicinity of the University.',
                'category' => 'Procedural',
                'severity_level' => 'Minor',
                'standard_sanction' => '1st: Reprimand; 2nd: 5 class days suspension; 3rd: Dismissal',
            ],

            // J. ILLEGAL ENTRY AND EXIT
            [
                'code' => 'IE-J1',
                'title' => 'Using Illegal Routes',
                'description' => 'Entering in and exiting from the campus using illegal routes.',
                'category' => 'Procedural',
                'severity_level' => 'Minor',
                'standard_sanction' => '1st: Reprimand; 2nd: 5 class days suspension; 3rd: Dismissal',
            ],

            // K. INTELLECTUAL DISHONESTY, CHEATING, PLAGIARISM
            [
                'code' => 'ID-K1',
                'title' => 'Plagiarism',
                'description' => 'Plagiarism.',
                'category' => 'Academic',
                'severity_level' => 'Major',
                'standard_sanction' => '1st: 5 class days suspension; 2nd: Dismissal',
            ],
            [
                'code' => 'ID-K2',
                'title' => 'Cheating and Academic Dishonesty',
                'description' => 'Intellectual dishonesty, cheating in examinations and taking the possession of or passing exam leakages and taking exams by proxy.',
                'category' => 'Academic',
                'severity_level' => 'Major',
                'standard_sanction' => '1st: Failing grade + 5 days suspension; 2nd: Dismissal',
            ],

            // L. FALSIFICATION OF RECORDS, DOCUMENTS AND CREDENTIALS
            [
                'code' => 'FR-L1',
                'title' => 'Document Falsification',
                'description' => 'Forging, falsifying, or tampering university records, documents, or credentials, or knowingly furnishing the university with fraudulent information in connection with an official document.',
                'category' => 'Academic',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: 5 days suspension; 2nd: 1 semester suspension; 3rd: Dismissal',
            ],
            [
                'code' => 'FR-L2',
                'title' => 'Fake/Tampered ID',
                'description' => 'Entering the campus with fake, tampered or borrowed ID.',
                'category' => 'Procedural',
                'severity_level' => 'Moderate',
                'standard_sanction' => '1st: Reprimand; 2nd: 5 class days suspension; 3rd: Dismissal',
            ],

            // M. MALVERSATION OF FUND
            [
                'code' => 'MF-M1',
                'title' => 'Malversation ₱5000 and Below',
                'description' => 'Malversation of fund by any student, organization, class or group - ₱5000 and below.',
                'category' => 'Behavioral',
                'severity_level' => 'Major',
                'standard_sanction' => '1st: 5 days suspension + payment; 2nd: Dismissal + payment',
            ],
            [
                'code' => 'MF-M2',
                'title' => 'Malversation ₱5001-₱10000',
                'description' => 'Malversation of fund by any student, organization, class or group - ₱5001-₱10000.',
                'category' => 'Behavioral',
                'severity_level' => 'Major',
                'standard_sanction' => '1st: 5 days suspension + payment; 2nd: Dismissal + payment',
            ],
            [
                'code' => 'MF-M3',
                'title' => 'Malversation ₱10001 and Above',
                'description' => 'Malversation of fund by any student, organization, class or group - ₱10001 and above.',
                'category' => 'Behavioral',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: Dismissal + payment',
            ],

            // N. GAMBLING
            [
                'code' => 'GB-N1',
                'title' => 'Gambling Inside Campus',
                'description' => 'Gambling inside the campus with or without cash except authorized bingo socials.',
                'category' => 'Behavioral',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: Dismissal',
            ],

            // E. OTHER OFFENSES
            [
                'code' => 'OT-01',
                'title' => 'Smoking Inside University Premises',
                'description' => 'Smoking inside the University premises.',
                'category' => 'Procedural',
                'severity_level' => 'Minor',
                'standard_sanction' => '1st: Reprimand; 2nd: 5 class days suspension; 3rd: Dismissal',
            ],
            [
                'code' => 'OT-02',
                'title' => 'Distributing Pornographic Materials',
                'description' => 'Distributing and selling objects, pictures and literature that are pornographic in nature.',
                'category' => 'Behavioral',
                'severity_level' => 'Major',
                'standard_sanction' => '1st: Reprimand; 2nd: 5 class days suspension; 3rd: Dismissal',
            ],
            [
                'code' => 'OT-03',
                'title' => 'Uniform/ID Violation',
                'description' => 'Not wearing of ID and prescribed uniform inside the campus.',
                'category' => 'Procedural',
                'severity_level' => 'Minor',
                'standard_sanction' => '1st: Reprimand; 2nd: 5 class days suspension; 3rd: Dismissal',
            ],
            [
                'code' => 'OT-04',
                'title' => 'Unauthorized Use of University Facilities',
                'description' => 'Unauthorized use of the university facilities.',
                'category' => 'Procedural',
                'severity_level' => 'Minor',
                'standard_sanction' => '1st: Reprimand; 2nd: 5 class days suspension; 3rd: Dismissal',
            ],
            [
                'code' => 'OT-05',
                'title' => 'Unauthorized Assembly During Class Hours',
                'description' => 'Unauthorized assembly of students even in small groups within the university during class hours.',
                'category' => 'Procedural',
                'severity_level' => 'Minor',
                'standard_sanction' => '1st: Reprimand; 2nd: 5 class days suspension; 3rd: Dismissal',
            ],
            [
                'code' => 'OT-06',
                'title' => 'Creating Undue Noise or Disturbances',
                'description' => 'Undue noise or disturbances in classrooms, library, quarters and other places within the University.',
                'category' => 'Procedural',
                'severity_level' => 'Minor',
                'standard_sanction' => '1st: Reprimand; 2nd: 5 class days suspension; 3rd: Dismissal',
            ],
            [
                'code' => 'OT-07',
                'title' => 'Unauthorized Activity in Restricted Areas',
                'description' => 'Sleeping, cooking and doing toilet necessities in unauthorized places.',
                'category' => 'Procedural',
                'severity_level' => 'Minor',
                'standard_sanction' => '1st: Reprimand; 2nd: 5 class days suspension; 3rd: Dismissal',
            ],
            [
                'code' => 'OT-08',
                'title' => 'Dress Code Violations',
                'description' => 'Wearing earrings for male inside the school premises and loud-colored hair and body piercings for both male and female.',
                'category' => 'Procedural',
                'severity_level' => 'Minor',
                'standard_sanction' => '1st: Reprimand; 2nd: 5 class days suspension; 3rd: Dismissal',
            ],

            // F. ICT POLICY - MAJOR OFFENSES
            [
                'code' => 'ICT-MAJ-A',
                'title' => 'Discourtesy/Cyberbullying',
                'description' => 'Discourtesy/impolite conduct, defamation and/or libel, bullying in any form (oral, written, or through electronic media).',
                'category' => 'Technology',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: Dismissal (may be meted) + written apology, depending on gravity',
            ],
            [
                'code' => 'ICT-MAJ-B',
                'title' => 'Cyber-crimes and Indecent Content',
                'description' => 'Taking/uploading, distributing indecent/obscene photos or videos, hacking, computer-related forgery and other forms of cyber-crime pursuant to Republic Act No. 10175 otherwise known as "The Cybercrime Prevention Act of 2012".',
                'category' => 'Technology',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: Dismissal (may be meted) + written apology, depending on gravity',
            ],
            [
                'code' => 'ICT-MAJ-C',
                'title' => 'Social Media Grievances/Attacks',
                'description' => 'Posting on social media grievances, sentiments, personal attacks, insulting remarks, or other forms.',
                'category' => 'Technology',
                'severity_level' => 'Severe',
                'standard_sanction' => '1st: Dismissal (may be meted) + written apology, depending on gravity',
            ],

            // F. ICT POLICY - MINOR OFFENSES
            [
                'code' => 'ICT-MIN-A1',
                'title' => 'Inciting Non-Attendance Online',
                'description' => 'Misbehavior during online classes: Inciting other students not to attend online learning platforms.',
                'category' => 'Technology',
                'severity_level' => 'Minor',
                'standard_sanction' => 'Progressive discipline per offense count',
            ],
            [
                'code' => 'ICT-MIN-A2',
                'title' => 'Instigating Online Quarrels',
                'description' => 'Misbehavior during online classes: Instigating quarrels between students, whether visual or written, via social media.',
                'category' => 'Technology',
                'severity_level' => 'Minor',
                'standard_sanction' => 'Progressive discipline per offense count',
            ],
            [
                'code' => 'ICT-MIN-A3',
                'title' => 'Using Offensive Language Online',
                'description' => 'Misbehavior during online classes: Using profanity, racial slurs, or other language (text, sound, or hint) that may be offensive to any other user.',
                'category' => 'Technology',
                'severity_level' => 'Moderate',
                'standard_sanction' => 'Progressive discipline per offense count',
            ],
            [
                'code' => 'ICT-MIN-A4',
                'title' => 'Unauthorized Account Deletion',
                'description' => 'Misbehavior during online classes: Unauthorized deletion of the teacher or students\' online account during classes.',
                'category' => 'Technology',
                'severity_level' => 'Major',
                'standard_sanction' => 'Progressive discipline per offense count',
            ],
            [
                'code' => 'ICT-MIN-B',
                'title' => 'Unauthorized Sharing of Media',
                'description' => 'Unauthorized sharing of photos and video recordings.',
                'category' => 'Technology',
                'severity_level' => 'Moderate',
                'standard_sanction' => 'Progressive discipline per offense count',
            ],
            [
                'code' => 'ICT-MIN-C',
                'title' => 'Unauthorized Recording of Classes',
                'description' => 'Unauthorized taking of pictures/recording (audio/video) of the class discussion during online and face to face classes.',
                'category' => 'Technology',
                'severity_level' => 'Moderate',
                'standard_sanction' => 'Progressive discipline per offense count',
            ],
            [
                'code' => 'ICT-MIN-D',
                'title' => 'Unauthorized Electronic Device Use',
                'description' => 'Unauthorized use of cellphones and other electronic gadgets during class unless allowed by the faculty concerned for academic purposes.',
                'category' => 'Technology',
                'severity_level' => 'Minor',
                'standard_sanction' => 'Progressive discipline per offense count',
            ],
        ];

        foreach ($offenseRules as $rule) {
            // Auto-assign gravity based on severity_level to align with CSU Manual terminology
            // Minor Offenses vs Major Offenses classification
            $rule['gravity'] = match ($rule['severity_level']) {
                'Minor' => 'minor',
                'Moderate', 'Major', 'Severe' => 'major',
                default => 'other',
            };

            OffenseRule::create($rule);
        }
    }
}
