#! /usr/bin/env python
# -*- coding: utf-8 -*-

"""
This file will be run by an existing cron job set by the PHP script.
Call this script like this:

$ ./set_reserved.py -q queue_id [-r]

queue_id is the id of the to be reserved item. This script will move the
reservation from the queue to the reserved_items table. If the -r flag is set,
it will also send the user a reminder to return the item when it's due.
"""

import getopt
import sys
try:
    import MySQLdb
except ImportError:
    print 'This script requires MySQLdb. Please install the MySQL-python package.'
    sys.exit(2)

def usage():
    print 'Usage: {} -q queue_id [-r]'.format(sys.argv[0])
    print '\t-q, --queue_id'
    print '\t\t\tqueue_id to be moved to the reserved_items table'
    print '\t-r, --reminder'
    print '\t\t\tenable reminder cron job'

def connect(host, user, passwd, db):
    """Return a MySQL database cursor if connection succeeds"""

    db = MySQLdb.connect(host   = host,
                         user   = user,
                         passwd = passwd,
                         db     = db)
    return db.cursor()

def get_queue_item(queue_id):
    sql = 'SELECT * FROM queue WHERE id={}'.format(queue_id)
    return db.execute(sql)

def create_reservation(queue_item):
    if not queue_item:
        return False

    for a, b in enumerate(queue_item):
        print a, b

    sql = 'INSERT INTO reserved_items (id, item_id, user_id, reserved_at, \
                                       reserved_from, reserved_until, \
                                       reserve_time, returned, returned_at, \
                                       count) \
                               VALUES (None, {0}, {1}, {2}, {3}, {4}, {5}, {6},\
                                       {7}, {8})'.format()

def main():
    if not len(sys.argv) > 1:
        usage()
        sys.exit(2)

    try:
        opts, args = getopt.getopt(sys.argv[1:], 'rq:', ['reminder', 'queue_id='])
    except getopt.GetoptError as err:
        print '{}: {}'.format(sys.argv[0], err)
        sys.exit(2)

    queue_id       = None
    send_reminders = False

    for opt, arg in opts:
        if opt in ('-r', '--reminder'):
            send_reminders = True
        elif opt in ('-q', '--queue_id'):
            queue_id = arg

    if not queue_id:
        usage()
        sys.exit(2)

    queue_item = get_queue_item(queue_id)
    create_reservation(queue_item)


if __name__ == '__main__':
    db = connect('localhost', 'reserver', 'smtjres', 'reserver')

    main()
